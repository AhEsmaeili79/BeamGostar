# admin.py
from django.contrib import admin
from .models import PaymentMethod

@admin.register(PaymentMethod)
class PaymentMethodAdmin(admin.ModelAdmin):
    list_display = ('title', 'status', 'created_at', 'updated_at', 'deleted_at')
    list_filter = ('status', 'created_at')
    search_fields = ('title',)
    ordering = ('-created_at',)
