@include('students.pages.vocabulary-practise._form',[
'route' => route('student.vocabularies.storeResult', $model),
'method' => 'POST',
'label' => 'Сохранить',
])
