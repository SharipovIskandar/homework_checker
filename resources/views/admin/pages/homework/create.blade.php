@include('admin.pages.homework._form',[
'subjects' => $subjects,
'homeworkTypes' => $homeworkTypes,
'route' => route('admin.homework.store'),
'method' => 'POST',
// 'langs' => $langs,
// 'translateList' => $translateList,
'label' => 'Сохранить',
])
