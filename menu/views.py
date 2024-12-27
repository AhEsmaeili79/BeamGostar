from django.shortcuts import render
from .models import Menu

def menu_view(request):
    # Fetch active menus with their submenus and subitems in one query
    menus = Menu.objects.filter(parent__isnull=True, is_active=True).prefetch_related(
        'submenus__subitems'  # Prefetch submenus and their subitems
    )
    
    # Build the menu structure
    menu_structure = []
    for menu in menus:
        submenus = menu.submenus.filter(is_active=True).order_by('order')
        menu_structure.append({
            'name': menu.name,
            'title': menu.title,
            'icon': menu.icon,
            'url': menu.url or f"#{menu.name}",  # Default to submenu toggle if URL is not defined
            'submenus': [
                {
                    'name': submenu.name,
                    'title': submenu.title,
                    'icon': submenu.icon,
                    'url': submenu.url or f"#{submenu.name}",  # Default to submenu toggle if URL is not defined
                    'subitems': [
                        {
                            'title': subitem.title,
                            'url': subitem.url or f"#{subitem.title}",  # Default to submenu toggle if URL is not defined
                        } for subitem in submenu.subitems.filter(is_active=True).order_by('submenu__order')
                    ]
                } for submenu in submenus
            ]
        })

    return render(request, 'partials/_menu.html', {'menu_structure': menu_structure})
