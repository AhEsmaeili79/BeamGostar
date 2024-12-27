from django.urls import path
from . import views

app_name = 'admin_panel'

urlpatterns = [
    path('', views.DashboardView.as_view(), name='dashboard'),  # Use as_view() method to create an instance
    path('login', views.user_login, name='login'),  # Make sure the URL has a trailing slash
    path('user/add/', views.user_add_or_edit, name='user_add'),
    path('user/edit/<int:user_id>/', views.user_add_or_edit, name='user_edit'),
]