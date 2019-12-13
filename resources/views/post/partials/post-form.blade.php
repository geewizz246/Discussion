<div class="form-group row">
    {{ Form::label("post", __('*Reply:'), [
        "for" => "post", "class" => "col-4 col-sm-3 col-md-4 col-lg-4 col-form-label text-sm-right text-md-right"
    ]) }}

    <div class="col-sm-8 col-md-6 col-lg-6">
        {{ Form::textarea("post", $post->body, [
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
        <button type="submit" class="btn btn-success p-2 m-2">Post Reply</button>
        <a href="{{ route('discussion.index') }}" class="btn btn-danger p-2 m-2">Cancel</a>
    </div>
</div>