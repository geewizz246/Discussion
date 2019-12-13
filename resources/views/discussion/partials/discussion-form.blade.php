<div class="form-group row">
    {{ Form::label('topic', __('*Topic:'), [
        "for" => "topic",  "class" => "col-4 col-sm-3 col-md-4 col-lg-4 col-form-label text-sm-right text-md-right"
    ]) }}

    <div class="col-sm-8 col-md-6 col-lg-6">
        {{ Form::text('topic', $discussion->topic, [
            "class" => "form-control " . ($errors->first('topic') ? "is-invalid" : ""), "value" => old('topic'),
            "required", "autocomplete" => "topic", "autofocus"
        ]) }}

        @error('topic')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label("description", __('*Description:'), [
        "for" => "description", "class" => "col-4 col-sm-3 col-md-4 col-lg-4 col-form-label text-sm-right text-md-right" 
    ]) }}

    <div class="col-sm-8 col-md-6 col-lg-6">
        {{ Form::textarea("description", $discussion->description, [
            "class" => "form-control " . ($errors->first('description') ? "is-invalid" : ""), "value" => old('description'), "required",
            "autocomplete" => "description", "autofocus", "placeholder" => __("Please give this discussion topic a description.")
        ]) }}

        @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label("post", __('*Initial Post:'), [
        "for" => "post", "class" => "col-4 col-sm-3 col-md-4 col-lg-4 col-form-label text-sm-right text-md-right"
    ]) }}

    <div class="col-sm-8 col-md-6 col-lg-6">
        {{ Form::textarea("post", ($discussion->posts()->count() > 0 ? $discussion->posts()->first()->body : '' ), [
            "class" => "form-control " . ($errors->first('post') ? "is-invalid" : ""), "value" => old('post'), "required",
            "autocomplete" => "post", "autofocus", "placeholder" => __('Enter your post here.')
        ]) }}

        @error('post')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label("attachments", __('Attachments:'), [
        'for' => "attachments", "class" => "col-4 col-sm-3 col-md-4 col-lg-4 col-form-label text-sm-right text-md-right"
    ]) }}

    <div class="col-sm-8 col-md-6 col-lg-6">
        <input id="attachments" type="file" multiple class="form-control-file @error('attachments.*') is-invalid @enderror" name="attachments[]">

        @error('attachments.*')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group">
    <div class="text-center">
        <button type="submit" class="btn btn-success p-2 m-2">Post to Forum</button>
        <a href="{{ route('discussion.index') }}" class="btn btn-danger p-2 m-2">Cancel</a>
    </div>
</div>