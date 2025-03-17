@include('admin.pages.homework-types._form', [
'model' => $model,
'route' => route('admin.homework-types.update', $model),
'method' => 'PUT',
'label' => 'Edit',
])
