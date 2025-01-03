from django.contrib import admin
from .models import Menu, SubMenu, SubItem

# Register Menu Model
@admin.register(Menu)
class MenuAdmin(admin.ModelAdmin):
    list_display = ('id','title','name', 'icon', 'order', 'state','url')
    search_fields = ('name', 'title', 'icon')
    ordering = ('order',)  # Default ordering based on 'order' field
    prepopulated_fields = {'title': ('name',)}  # Automatically fill 'title' field based on 'name'

# Register SubMenu Model
@admin.register(SubMenu)
class SubMenuAdmin(admin.ModelAdmin):
    list_display = ('id','title','menu', 'url', 'icon', 'order', 'state')
    list_filter = ('state', 'menu')  # Filtering based on state  and associated menu
    search_fields = ('title', 'url', 'icon')
    ordering = ('order',)  # Default ordering based on 'order' field
    prepopulated_fields = {'title': ('menu',)}  # Automatically fill 'title' field based on associated menu

# Register SubItem Model
@admin.register(SubItem)
class SubItemAdmin(admin.ModelAdmin):
    list_display = ('id','title', 'submenu', 'url', 'state')
    list_filter = ('state', 'submenu')  # Filtering based on state  and associated submenu
    search_fields = ('title', 'url')
    ordering = ('submenu__order',)  # Default ordering based on 'submenu' order


