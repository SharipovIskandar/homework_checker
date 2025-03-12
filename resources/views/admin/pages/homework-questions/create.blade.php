@include('admin.pages.homework-questions._form',[
'route' => route('admin.homework-questions.store'),
'method' => 'POST',
'homeworks' => $homeworks,
// 'langs' => $langs,
// 'translateList' => $translateList,
'label' => 'Сохранить',
])
