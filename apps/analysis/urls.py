from django.urls import path
from . import views

urlpatterns = [
    # analysis
    path("analysis/list/", views.analyze_list_view, name="analyze_list"),
    path('analysis/timelist/', views.analysis_time_view, name='analysis_time'),
    # answers
    path('answers/', views.answers_list, name='answers_list'),
    path('answers/add/', views.add_answer, name='add_answer'),
    path('answers/edit/<int:id>/', views.edit_answer, name='edit_answer'),
    path('answers/delete/<int:id>/', views.delete_answer, name='delete_answer'),
    path('answers/<int:id>/', views.get_answer_by_id, name='get_answer_by_id'),
    # analysis-person
    path('link-analysis-person/', views.link_analysis_person_list, name='link_analysis_person_list'),

]
