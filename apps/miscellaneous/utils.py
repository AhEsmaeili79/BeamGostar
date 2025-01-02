from .models import Menu

def get_menu_structure():
    """
    Fetch all active menus, submenus, and subitems from the database, and build the structure.
    """
    # Fetch active menus
    menus = Menu.objects.filter(state=True).prefetch_related('submenus__subitems')
    
    menu_structure = []
    for menu in menus:
        # Filter active submenus
        submenus = menu.submenus.filter(state=True).order_by('order')  # Filter active submenus
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
                        } for subitem in submenu.subitems.filter(state=True).order_by('submenu__order')  # Ensure only active subitems
                    ]
                } for submenu in submenus
            ]
        })

    return menu_structure
