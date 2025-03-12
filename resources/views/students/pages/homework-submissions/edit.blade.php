@include('students.pages.homework-submissions._form', [
'model' => $model,
'route' => route('student.homework.submissions.update', $model),
'method' => 'PUT',
'label' => 'Изменить',
])
