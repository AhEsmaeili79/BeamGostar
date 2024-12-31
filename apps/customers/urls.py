from django.urls import path
from . import views

urlpatterns = [
    path('payment-methods/', views.payment_method_list, name='payment_method_list'),
]
