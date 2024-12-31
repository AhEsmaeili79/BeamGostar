# context_processors.py
from .utils import get_menu_structure

def menu_context_processor(request):
    return {'menu_structure': get_menu_structure()}
