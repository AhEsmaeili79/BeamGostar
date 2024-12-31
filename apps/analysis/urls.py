from django.urls import path
from .views import analyze_list_view,analysis_time_view

urlpatterns = [
    path("analyze/list/", analyze_list_view, name="analyze_list"),
    path('analysis/timelist/', analysis_time_view, name='analysis_time'),
]
