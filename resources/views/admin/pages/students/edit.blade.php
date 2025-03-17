@include('admin.pages.students._form', [
'model' => $model,
'route' => route('admin.students.update', $model),
'method' => 'PUT',
'label' => 'Edit',
])
