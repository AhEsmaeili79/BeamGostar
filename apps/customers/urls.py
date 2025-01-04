from django.urls import path
from . import views

urlpatterns = [
    path('payment-methods/', views.payment_method_list, name='payment_method_list'),
    path('price_analysis/', views.price_analysis_list, name='price_analysis_list'),
    path('price_analysis_credit/', views.price_analysis_credit_list, name='price-analysis-credit'),
    # Customers
    path('customers/', views.customer_list, name='customer_list'),
    path('customers/add', views.customer_add, name='customer_add'),
    path('customers/edit/<int:customer_id>', views.customer_edit, name='customer_edit'),
    path('customers/delete/<int:customer_id>', views.customer_delete, name='customer_delete'),
    path('customers/detail/<int:customer_id>', views.customer_detail, name='customer_detail'),
]
