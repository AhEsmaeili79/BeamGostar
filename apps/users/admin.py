from django.contrib import admin
from .models import User, UserActivity, AuthAssignment, AuthItem, AuthItemChild, AuthMenu, AuthRule

# User Model Admin
class UserAdmin(admin.ModelAdmin):
    list_display = ('username', 'email', 'personnel', 'reg_date', 'state', 'lastactive', 'accounttype_id')
    search_fields = ('username', 'email')
    list_filter = ('state', 'accounttype_id', 'reg_date')
    ordering = ('-reg_date',)

admin.site.register(User, UserAdmin)

# UserActivity Model Admin
class UserActivityAdmin(admin.ModelAdmin):
    list_display = ('user', 'item_name', 'date', 'ip', 'lat', 'lng', 'record_id', 'table_name')
    search_fields = ('user__username', 'item_name', 'ip')
    list_filter = ('date',)

admin.site.register(UserActivity, UserActivityAdmin)

# AuthAssignment Model Admin
class AuthAssignmentAdmin(admin.ModelAdmin):
    list_display = ('item_name', 'user', 'created_at')
    search_fields = ('item_name', 'user__username')
    list_filter = ('created_at',)

admin.site.register(AuthAssignment, AuthAssignmentAdmin)

# AuthItem Model Admin
class AuthItemAdmin(admin.ModelAdmin):
    list_display = ('name', 'type', 'description', 'rule_name', 'created_at', 'updated_at')
    search_fields = ('name', 'description', 'rule_name')
    list_filter = ('type', 'created_at', 'updated_at')

admin.site.register(AuthItem, AuthItemAdmin)

# AuthItemChild Model Admin
class AuthItemChildAdmin(admin.ModelAdmin):
    list_display = ('parent', 'child', 'updated_at')
    search_fields = ('parent', 'child')
    list_filter = ('updated_at',)

admin.site.register(AuthItemChild, AuthItemChildAdmin)

# AuthMenu Model Admin
class AuthMenuAdmin(admin.ModelAdmin):
    list_display = ('item_name', 'menu_id', 'updated_at')
    search_fields = ('item_name',)
    list_filter = ('menu_id', 'updated_at')

admin.site.register(AuthMenu, AuthMenuAdmin)

# AuthRule Model Admin
class AuthRuleAdmin(admin.ModelAdmin):
    list_display = ('name', 'data', 'created_at', 'updated_at')
    search_fields = ('name',)
    list_filter = ('created_at', 'updated_at')

admin.site.register(AuthRule, AuthRuleAdmin)
