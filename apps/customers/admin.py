# admin.py
from django.contrib import admin
from .models import PaymentMethod,PriceAnalysis, PriceAnalysisCredit, Customer

@admin.register(PaymentMethod)
class PaymentMethodAdmin(admin.ModelAdmin):
    list_display = ('title', 'state', 'created_at', 'updated_at', 'deleted_at')
    list_filter = ('state', 'created_at')
    search_fields = ('title',)
    ordering = ('-created_at',)

admin.site.register(PriceAnalysis)
admin.site.register(PriceAnalysisCredit)

class CustomerAdmin(admin.ModelAdmin):
    list_display = ('id', 'name_fa','family_fa', 'customer_type', 'nationality', 'clearing_type', 'state')
    list_filter = ('state', 'created_at')
    search_fields = ('title',)
    ordering = ('-created_at',)
admin.site.register(Customer,CustomerAdmin)
