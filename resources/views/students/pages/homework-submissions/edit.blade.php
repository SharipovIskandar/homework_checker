@include('admin.pages.homework._form', [
'subjects' => $subjects,
'homeworkTypes' => $homeworkTypes,
'model' => $model,
'route' => route('admin.homework.update', $model),
'method' => 'PUT',
'label' => 'Изменить',
])
