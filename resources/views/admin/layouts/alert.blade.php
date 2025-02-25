@if (session()->has('success'))
<div class="alert alert-success alert-dismissible">
    {{ session('success') }}
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
</div>
<script>
    setTimeout(function(){ $('.alert').hide(); }, 10000);
</script>
@endif

@if (session()->has('not-allowed'))
<div class="alert alert-warning alert-dismissible">
    {{ session('not-allowed') }}
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
</div>
<script>
    setTimeout(function(){ $('.alert').hide(); }, 10000);
</script>
@endif

@if (count($errors->interpolation) > 0)
<div class="alert small alert-danger">
    {{ $errors->interpolation->first() }}
</div>
@endif