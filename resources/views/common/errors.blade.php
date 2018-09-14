<!-- resources/views/common/errors.blade.php -->

@if (count($errors) > 0)
    <!-- Form error list -->
    <div class="alert alert-danger">
        <strong>Something went wrong!</strong>
        <br><br>

        <ul>
            @foreach ($errors->val() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif