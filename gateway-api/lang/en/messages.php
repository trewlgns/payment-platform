<?php

return [
    // Common messages
    "success" => "Success",
    "error" => "An error occurred",
    "not_found" => "Resource not found",
    "created" => "Created successfully",
    "updated" => "Updated successfully",
    "deleted" => "Deleted successfully",
    "validation_failed" => "Validation failed",
    "unauthorized" => "Authentication required",
    "forbidden" => "Access denied",
    "server_error" => "Internal server error",
    "db_binding_error" => "Database binding format is invalid",

    // Product related
    "product_not_found" => "Product not found",
    "product_list_success" => "Product list retrieved successfully",
    "product_detail_success" => "Product details retrieved successfully",
    "product_created" => "Product created successfully",
    "product_updated" => "Product updated successfully",
    "product_deleted" => "Product deleted successfully",
    "product_status_updated" => "Product status updated successfully",
    "product_out_of_stock" => "Product out of stock",
    "product_code_exists" => "Product code already exists",
    "product_inactive" => "Product is inactive",
    "product_delete_failed" => "Failed to delete product",
    // Order related
    "order_not_found" => "Order not found",
    "order_list_success" => "Order list retrieved successfully",
    "order_detail_success" => "Order details retrieved successfully",
    "order_created" => "Order created successfully",
    "order_cancelled" => "Order cancelled successfully",
    "order_cannot_cancel" => "Cannot cancel order in current status",
    "order_status_updated" => "Order status updated successfully",

    // Payment related
    "payment_not_found" => "Payment not found",
    "payment_success" => "Payment completed successfully",
    "payment_failed" => "Payment failed",
    "payment_cancelled" => "Payment cancelled successfully",
    "payment_refunded" => "Payment refunded successfully",
    "payment_already_processed" => "Payment already processed",
    "payment_amount_mismatch" => "Payment amount mismatch",

    // User related
    "user_not_found" => "User not found",
    "user_created" => "User created successfully",
    "user_updated" => "User information updated successfully",
    "user_deleted" => "User deleted successfully",
    "user_email_exists" => "Email is already in use",
    "user_inactive" => "User is inactive",
    "user_deleted_state" => "User has been deleted",
    "user_email_not_verified" => "Email verification is required",
    "user_email_already_verified" => "Email has already been verified",
    "user_update_failed" => "Failed to update user information",
    "user_delete_failed" => "Failed to delete user",
    "user_status_update_failed" => "Failed to update user status",
    "email_verification_sent" => "Verification email has been sent",
    "email_verification_failed" => "Failed to verify email",
    "verification_token_required" => "Verification token is required",
    "verification_token_not_found" => "Verification token does not exist",
    "verification_token_mismatch" => "Email and token do not match",
    "verification_token_expired" => "Verification token has expired",
    "verification_token_used" => "Verification token has already been used",

    // Coupon related
    "coupon_not_found" => "Coupon not found",
    "coupon_expired" => "Coupon has expired",
    "coupon_already_used" => "Coupon already used",
    "coupon_applied" => "Coupon applied successfully",

    // Promotion related
    "promotion_not_found" => "Promotion not found",
    "promotion_not_active" => "Promotion is not active",
    "promotion_applied" => "Promotion applied successfully",

    // Refund related
    "refund_requested" => "Refund requested successfully",
    "refund_approved" => "Refund approved successfully",
    "refund_rejected" => "Refund rejected",
    "refund_completed" => "Refund completed successfully",

    // Validation messages
    "validation" => [
        "user" => [
            "email_required" => "Email is required.",
            "email_email" => "Please provide a valid email address.",
            "email_unique" => "This email is already in use.",
            "password_required" => "Password is required.",
            "password_min" => "Password must be at least :min characters.",
            "name_required" => "Name is required.",
            "name_string" => "Name must be a string.",
            "name_max" => "Name may not be greater than :max characters.",
            "phone_required" => "Phone number is required.",
            "phone_string" => "Phone number must be a string.",
            "phone_regex" => "Phone number format is invalid.",
            "status_required" => "Status is required.",
            "status_in" => "Status must be one of active, inactive, or banned.",
            "verification_token_required" => "Verification token is required.",
            "verification_token_string" => "Verification token must be a string.",
        ],
        "product" => [
            "product_code_required" => "Product code is required.",
            "product_code_unique" => "Product code is already in use.",
            "name_required" => "Product name is required.",
            "base_price_required" => "Base price is required.",
            "base_price_numeric" => "Base price must be numeric.",
            "base_price_min" => "Base price must be at least 0.",
            "category_required" => "Category is required.",
            "category_max" => "Category may not be greater than :max characters.",
            "status_required" => "Status is required.",
            "status_in" => "Status must be one of active, inactive, or soldout.",
        ],
        "pagination" => [
            "page_integer" => "Page must be an integer.",
            "page_min" => "Page must be at least 1.",
            "per_page_integer" => "Per page must be an integer.",
            "per_page_min" => "Per page must be at least 1.",
            "per_page_max" => "Per page may not be greater than 100.",
        ],
    ],
];
