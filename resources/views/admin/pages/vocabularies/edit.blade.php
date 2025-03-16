@include('admin.pages.vocabularies._form', [
'model' => $model,
'route' => route('admin.vocabularies.update', $model),
'method' => 'POST',
'label' => 'Изменить',
])
