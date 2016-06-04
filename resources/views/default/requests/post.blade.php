<div class="card card-block" style="border-color:#55b346;">
	<p class="card-text">
		<img src="{{ App\User::Gravatar($post->User()->first()) }}" class="img-circle" style="width:50px;height:50px;" />
		<a href="#">{{ $post->User()->first()->name }}</a>
		<span class="text-muted" title="Last Edited {{ date('d M', strtotime($post['updated_at'])) }}">{{ date('d M', strtotime($post['updated_at'])) }}</span>
	</p>
	<p class="card-text">
		{!! $app->Bbcode->renderText($post['post_content']) !!}
	</p>
</div>
