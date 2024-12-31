from django.urls import path
from . import views

urlpatterns = [
    path('payment-methods/', views.payment_method_list, name='payment_method_list'),
    path('price_analysis/', views.price_analysis_list, name='price_analysis_list'),
    path('price_analysis_credit/', views.price_analysis_credit_list, name='price-analysis-credit'),
    path('customers/', views.customer_list, name='customer_list'),
]
