@include('students.pages.homework-submissions._form',[
'route' => route('student.homework.submissions.store'),
'method' => 'POST',
// 'langs' => $langs,
// 'translateList' => $translateList,
'label' => 'Сохранить',
])
