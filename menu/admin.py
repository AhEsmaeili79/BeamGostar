from django.contrib import admin
from .models import  Menu,SubItem

@admin.register(Menu)
class MenuAdmin(admin.ModelAdmin):
    list_display = ('name', 'parent', 'title', 'icon', 'order', 'is_active')
    list_filter = ('is_active',)
    search_fields = ('name', 'title', 'icon')
    ordering = ('order',)
    
admin.site.register(SubItem)