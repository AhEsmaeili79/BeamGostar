from django.shortcuts import render, redirect, get_object_or_404
from django.core.paginator import Paginator
from django.http import JsonResponse
from django.utils import timezone
from datetime import datetime
from .models import GetAnswer,LinkAnalysisPerson

buttons = [
        {'text': 'راهنما', 'url': '#', 'icon': 'solar:question-circle-broken'},
        {'text': 'ثبت رکورد جدید',  'url': '#', 'icon': 'solar:add-circle-broken'},
        {'text': 'پرینت', 'url': '#', 'icon': 'solar:printer-bold'},
        {'text': 'حذف چندتایی', 'url': '#', 'icon': 'solar:trash-bin-minimalistic-2-broken'},
        {'text': 'خروجی', 'url': '#', 'icon': 'solar:export-bold' },
        {'text': 'تنظیمات', 'url': '#', 'icon': 'solar:settings-outline'},
    ]

def analyze_list_view(request):
    # Sample Data
    analyzes = [
        {
            "name": f"تحلیل {i}",
            "creation_date": datetime.now().strftime("%Y-%m-%d"),
            "edit_date": datetime.now().strftime("%Y-%m-%d"),
            "status": "فعال" if i % 2 == 0 else "غیرفعال",
        }
        for i in range(1, 101)
    ]
    

    # Pagination
    paginator = Paginator(analyzes, 10)  # 10 items per page
    page_number = request.GET.get("page")
    page_obj = paginator.get_page(page_number)

    context = {
        "page_obj": page_obj,
        "buttons" : buttons,
    }
    return render(request, 'dashboard/analysis/analyze-list.html', context)
    

def analysis_time_view(request):
    # Mock data - replace with database query
    ANALYSIS_DATA = [
        {
            "analysis_type": "XRD normal",
            "based_on": "روز",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "VSM",
            "based_on": "دقیقه",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "Zeta",
            "based_on": "دقیقه",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "آنالیز حرارتی",
            "based_on": "روز",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        {
            "analysis_type": "XRD normal",
            "based_on": "روز",
            "count": 10,
            "minutes": 100,
            "creation_date": "2024-01-01",
            "edit_date": "2024-01-05",
        },
        # Add all other analysis types as per requirements
    ]
    
    filters = [
        {'id': 'executive_body', 'label': 'آنالیز', 'options': ['xrd','XRF','CHN','CHNS','ICP']},
        {'id': 'status', 'label': 'برحسب', 'options': ['روز', 'دقیقه']},
    ]
    page_number = request.GET.get('page', 1)
    paginator = Paginator(ANALYSIS_DATA, 10)  # Show 10 items per page
    page_obj = paginator.get_page(page_number)

    context = {
        'analysis_time': page_obj,
        'page_obj': page_obj,
        'buttons':buttons,
        'filters':filters,
    }
    return render(request, 'dashboard/analysis/analysis-time-list.html', context)


# answers
def answers_list(request):
    # Fetch all answers
    answers = GetAnswer.objects.all()

    # Pagination
    paginator = Paginator(answers, 10)  # Show 10 answers per page
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)

    # Example buttons to be shown in the header
    
    return render(request, 'dashboard/analysis/answers/answers.html', {
        'buttons': buttons,
        'page_obj': page_obj,
    })
    
    
def add_answer(request):
    if request.method == 'POST':
        title = request.POST.get('title')
        status = request.POST.get('status')
        
        if title and status:
            # Create a new GetAnswer record
            GetAnswer.objects.create(title=title, status=status)
            return redirect('answers_list')  # Redirect to the answers list page
        
    return render(request, 'dashboard/analysis/answers/answers_edit.html')

def edit_answer(request, id):
    answer = get_object_or_404(GetAnswer, id=id)
    
    if request.method == 'POST':
        title = request.POST.get('title')
        status = request.POST.get('status')

        if title and status:
            # Update the existing GetAnswer record
            answer.title = title
            answer.status = status
            answer.updated_at = timezone.now()  # Update the updated_at field
            answer.save()
            return redirect('answers_list')  # Redirect to the answers list page
    
    return render(request, 'dashboard/analysis/answers/answers_edit.html', {'answer': answer})

def delete_answer(request, id):
    answer = get_object_or_404(GetAnswer, id=id)
    
    if request.method == 'POST':
        answer.deleted_at = timezone.now()  # Set deleted_at timestamp
        answer.save()
        return redirect('answers_list')  # Redirect to the answers list page
    
    return render(request, 'dashboard/analysis/answers/answers.html', {'answer': answer})

def get_answer_by_id(request, id):
    answer = get_object_or_404(GetAnswer, id=id)
    return JsonResponse({'id': answer.id, 'title': answer.title, 'status': answer.status})




def link_analysis_person_list(request):
    # Fetch the list of all LinkAnalysisPerson objects
    link_analysis_persons = LinkAnalysisPerson.objects.all()

    # Implement pagination
    paginator = Paginator(link_analysis_persons, 10)  # 10 items per page
    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)
    
    return render(request, 'dashboard/analysis/AnalysisPerson/analysisperson_list.html', {
        'page_obj': page_obj,
        'buttons': buttons
    })