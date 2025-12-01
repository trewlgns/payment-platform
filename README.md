# Payment Platform - 프로젝트 아키텍처

## 📋 프로젝트 구조

```
payment-platform/
├── gateway-api/          # Laravel MVC (Backend + Frontend)
├── analytics-service/    # Python 데이터 처리
└── mock-pg-server/       # Python FastAPI Mock PG
```

---

## 🏗️ Laravel Gateway API 레이어 구조

### 전체 레이어 흐름

```
HTTP Request
    ↓
Routes (라우팅)
    ↓
Middleware (인증/권한/로깅)
    ↓
Form Requests (요청 검증)
    ↓
Controllers (HTTP 요청/응답 처리)
    ↓
Services (비즈니스 로직) ← 커스텀 레이어
    ↓
Repositories (데이터 접근) ← 커스텀 레이어
    ↓
DB Class (PDO 래퍼) ← 커스텀 레이어
    ↓
MySQL Database
```

---

## 📊 프레임워크 레이어 비교

### CodeIgniter 4 vs Laravel vs 본 프로젝트

| 레이어 | CodeIgniter 4 | Laravel 기본 | 본 프로젝트 (Laravel) |
|--------|--------------|-------------|---------------------|
| **라우팅** | `Config/Routes.php` | `routes/api.php` | ✅ `routes/api.php` |
| **미들웨어/필터** | `Filters/` | `Middleware/` | ✅ `Middleware/` |
| **요청 검증** | ❌ 없음 | ✅ `Form Requests` | ✅ `Http/Requests/` |
| **컨트롤러** | `Controllers/` | `Controllers/` | ✅ `Http/Controllers/` |
| **서비스 레이어** | ❌ 커스텀 추가 필요 | ❌ 커스텀 추가 필요 | ✅ **`Services/` (커스텀 구현)** |
| **리포지토리 레이어** | ❌ 커스텀 추가 필요 | ❌ 커스텀 추가 필요 | ✅ **`Repositories/` (커스텀 구현)** |
| **데이터베이스 추상화** | Query Builder | Eloquent ORM | ✅ **`Database/DB.php` (PDO 래퍼, 커스텀)** |
| **모델** | `Models/` | `Models/` (Eloquent) | ⚠️ `Models/` (참조용, 직접 사용 안함) |
| **마이그레이션** | `Database/Migrations/` | `database/migrations/` | ✅ `database/migrations/` |
| **응답 변환** | ❌ 없음 | ✅ `Resources` | ⏸️ 필요시 추가 예정 |

---

## 🎯 핵심 차별화 포인트

### 1. Service-Repository 패턴 (커스텀 레이어)

**Laravel/CI4 기본 방식 (안티패턴)**:
```php
// Controller에 비즈니스 로직 + DB 접근 혼재
class ProductController extends Controller
{
    public function index()
    {
        // 비즈니스 로직 + DB 접근이 Controller에 직접
        $products = Product::where('status', 'active')
                          ->orderBy('created_at', 'desc')
                          ->get();
        return response()->json($products);
    }
}
```

**본 프로젝트 방식 (권장 패턴)**:
```php
// Controller: HTTP 요청/응답만 처리
class ProductController extends Controller
{
    public function index(ProductService $service)
    {
        $products = $service->getActiveProducts();
        return response()->json($products);
    }
}

// Service: 비즈니스 로직
class ProductService
{
    public function getActiveProducts(): array
    {
        return $this->productRepo->findActive();
    }
}

// Repository: 데이터 접근
class ProductRepository extends BaseRepository
{
    public function findActive(): array
    {
        $query = "SELECT * FROM products WHERE status = :status";
        return $this->db->select($query, [
            "status" => ["value" => "active", "type" => PDO::PARAM_STR]
        ]);
    }
}
```

**장점**:
- 단일 책임 원칙 (SRP) 준수
- 테스트 용이성
- 유지보수성 향상
- 비즈니스 로직 재사용 가능

---

### 2. PDO 기반 Static Query (성능 최적화)

**Laravel 기본 방식 (Query Builder/Eloquent)**:
```php
// ❌ 매번 다른 SQL 생성 → Library Cache 재사용 불가
Product::where('status', 'active')->get();
// SQL: select * from `products` where `status` = 'active'

Product::where('status', 'inactive')->get();
// SQL: select * from `products` where `status` = 'inactive'
// → DB가 완전히 다른 쿼리로 인식 → 하드파싱 반복
```

**본 프로젝트 방식 (PDO Prepared Statement)**:
```php
// ✅ 동일 쿼리 구조 → Library Cache Object 재사용
$query = "SELECT * FROM products WHERE status = :status";

$this->db->select($query, ["status" => ["value" => "active", "type" => PDO::PARAM_STR]]);
$this->db->select($query, ["status" => ["value" => "inactive", "type" => PDO::PARAM_STR]]);
// → DB가 동일 SQL로 인식 → 소프트파싱 (실행계획 재사용)
```

**성능 이점**:
- 하드파싱(파싱 + 문법검증 + 실행계획 생성) 회피
- 수백~수천 번 실행 시 성능 차이 극대화
- SQL Injection 방어
- 실행 계획 예측 가능

---

### 3. DB 클래스 설계 원칙

**쿼리 타입별 메서드 분리**:
```php
class DB
{
    public function select(string $query, array $bindings = []): array
    // → 반환: array (결과셋)

    public function selectOne(string $query, array $bindings = []): ?array
    // → 반환: array|null (단일 행)

    public function insert(string $query, array $bindings = []): int|string
    // → 반환: last insert id

    public function update(string $query, array $bindings = []): int
    // → 반환: affected rows

    public function delete(string $query, array $bindings = []): int
    // → 반환: affected rows
}
```

**이유**:
- 반환 타입이 명확히 다름 → 타입 힌팅으로 실수 방지
- PDO 메서드 차이 반영 (fetchAll vs fetch vs lastInsertId vs rowCount)

---

### 4. 트랜잭션 공유 (참조 주입)

```php
class BaseRepository
{
    // PDO 인스턴스를 참조(&)로 주입
    public function __construct(DB &$db)
    {
        $this->db = $db;
    }
}

// Service 레이어에서 트랜잭션 관리
class PaymentService
{
    public function processPayment(int $orderId): int
    {
        try {
            $this->db->beginTransaction();

            // 여러 Repository가 같은 DB 인스턴스 공유
            $payment = $this->paymentRepo->create([...]);
            $this->orderRepo->updateStatus($orderId, 'paid');

            $this->db->commit();
            return $payment;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
```

---

## 🔧 디렉토리 구조 (gateway-api)

```
app/
├── Database/
│   └── DB.php                      # PDO 래퍼 클래스
├── Entities/                       # Entity 레이어 (타입 안전성)
│   ├── UserEntity.php
│   ├── ProductEntity.php
│   ├── OrderEntity.php
│   └── EmailVerificationTokenEntity.php
├── Validators/                     # Semantic 검증 레이어
│   ├── BaseValidator.php
│   ├── UserValidator.php
│   ├── ProductValidator.php
│   └── OrderValidator.php
├── Repositories/
│   ├── BaseRepository.php          # 추상 Repository
│   ├── UserRepository.php
│   ├── ProductRepository.php
│   ├── OrderRepository.php
│   ├── PaymentRepository.php
│   └── EmailVerificationTokenRepository.php
├── Services/                       # 비즈니스 로직 (도메인별 폴더)
│   ├── BaseService.php             # 트랜잭션, 로깅 공통 처리
│   ├── User/
│   │   ├── ListUsersService.php
│   │   ├── GetUserService.php
│   │   ├── CreateUserService.php
│   │   ├── UpdateUserService.php
│   │   ├── DeleteUserService.php
│   │   ├── UpdateUserStatusService.php
│   │   ├── SendEmailVerificationService.php
│   │   └── VerifyUserEmailService.php
│   ├── Product/
│   │   ├── ListProductsService.php
│   │   ├── GetProductService.php
│   │   ├── CreateProductService.php
│   │   ├── UpdateProductService.php
│   │   ├── UpdateProductStatusService.php
│   │   └── DeleteProductService.php
│   └── Order/
│       ├── ListOrdersService.php
│       ├── GetOrderService.php
│       ├── CreateOrderService.php
│       ├── UpdateOrderStatusService.php
│       ├── CancelOrderService.php
│       └── RefundOrderService.php
├── Http/
│   ├── Controllers/
│   │   ├── BaseController.php      # Base Controller (success/fail/error)
│   │   ├── UserController.php
│   │   ├── ProductController.php
│   │   └── OrderController.php
│   ├── Requests/                   # 도메인별 폴더 구조
│   │   ├── User/
│   │   │   ├── ListUsersRequest.php
│   │   │   ├── CreateUserRequest.php
│   │   │   ├── UpdateUserRequest.php
│   │   │   ├── UpdateUserStatusRequest.php
│   │   │   └── VerifyEmailRequest.php
│   │   ├── Product/
│   │   │   ├── ListProductsRequest.php
│   │   │   ├── CreateProductRequest.php
│   │   │   ├── UpdateProductRequest.php
│   │   │   └── UpdateProductStatusRequest.php
│   │   └── Order/
│   │       ├── ListOrdersRequest.php
│   │       ├── CreateOrderRequest.php
│   │       └── UpdateOrderStatusRequest.php
│   └── Middleware/
│       └── SetLocale.php           # Accept-Language 처리
├── Exceptions/
│   ├── BaseException.php           # 자동 로깅, 민감정보 마스킹
│   ├── InvalidParameterException.php
│   ├── UnauthorizedException.php
│   ├── ForbiddenException.php
│   ├── NotFoundException.php
│   ├── ServerErrorException.php
│   └── ConflictException.php
├── Enums/
│   └── ResponseMessage.php         # 메시지 키 + 상태 코드 + 다국어
└── Providers/
    └── AppServiceProvider.php
```

---

## 🎓 기술 어필 포인트

1. **엔터프라이즈 아키텍처 패턴 적용**
   - Service-Repository 패턴으로 계층 분리
   - 단일 책임 원칙 (SRP) 준수
   - 의존성 주입 (DI) 활용

2. **성능 최적화 전략**
   - Static Query + Prepared Statement → Library Cache 재사용
   - 하드파싱 회피 → 대용량 트래픽 대응

3. **데이터베이스 설계 역량**
   - SQLP 자격증 기반 고급 쿼리 설계
   - 파티셔닝, 인덱스 최적화
   - 멱등성, 트랜잭션 관리

4. **프레임워크 깊은 이해**
   - 기본 제공 레이어와 커스텀 레이어 구분
   - 프레임워크 제약을 넘어선 설계 능력

5. **Exception 처리 및 로깅 전략**
   - 커스텀 Exception 시스템으로 일관된 에러 처리
   - 자동 로깅 및 민감정보 마스킹
   - 로그 채널 분리로 원인별 로그 관리
   - 자동 트랜잭션 롤백

6. **다국어 지원**
   - PHP Enum 기반 중앙 집중식 메시지 관리
   - 서버 사이드 다국어 처리 (Accept-Language 헤더)
   - 타입 안전성 보장 (IDE 자동완성)