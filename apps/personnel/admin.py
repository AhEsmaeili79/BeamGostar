from django.contrib import admin
from .models import Personnel

class PersonnelAdmin(admin.ModelAdmin):
    list_display = ('id', 'name', 'family', 'cooperation_id', 'personnel_num', 'national_code', 'certificate_number', 'father_name', 'sex', 'state', 'job_rank_id', 'job_type_id', 'department_id', 'work_range')
    search_fields = ('name', 'family', 'personnel_num', 'national_code', 'certificate_number')
    list_filter = ('state', 'sex', 'cooperation_id')
    ordering = ('id',)
    list_per_page = 20

admin.site.register(Personnel, PersonnelAdmin)
