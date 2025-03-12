@include('admin.pages.homework-types._form',[
'route' => route('admin.homework-types.store'),
'method' => 'POST',
// 'langs' => $langs,
// 'translateList' => $translateList,
'label' => 'Сохранить',
])
