from django.contrib import admin
from .models import GetAnswer,Analyze, LinkAnalysisPerson

class GetAnswerAdmin(admin.ModelAdmin):
    list_display = ('title', 'state_display', 'created_at', 'updated_at', 'deleted_at')
    list_filter = ('state', 'created_at')
    search_fields = ('title',)
    ordering = ('-created_at',)

    def state_display(self, obj):
        return "فعال" if obj.state == 1 else "غیرفعال"
    state_display.short_description = 'وضعیت'

admin.site.register(GetAnswer, GetAnswerAdmin)


@admin.register(Analyze)
class AnalyzeAdmin(admin.ModelAdmin):
    list_display = ('id', 'title', 'state', 'created_at', 'updated_at', 'deleted_at')
    search_fields = ('title',)

@admin.register(LinkAnalysisPerson)
class LinkAnalysisPersonAdmin(admin.ModelAdmin):
    list_display = ('id', 'personnel', 'analyze', 'date', 'time', 'created_at', 'updated_at', 'deleted_at')
    search_fields = ('personnel__name', 'analyze__title')