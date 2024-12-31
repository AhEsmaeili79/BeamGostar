from django.shortcuts import render
from .utils import get_menu_structure  # Import the utility function

def menu_view(request):
    # Use the utility function to get the menu structure
    menu_structure = get_menu_structure()
    
    # Pass the structure of the menus to the template
    return render(request, 'partials/_menu.html', {'menu_structure': menu_structure})


