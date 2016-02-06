<?php

use \Calctool\Models\Relation;
use \Calctool\Models\User;

$relation_self = Relation::find(User::find(Input::get('uid'))->self_id);
if ($relation_self) {
?>
<body>
	<span style="font-family:arial,helvetica,sans-serif;color:#696969;">
		<span style="font-size:10px;">
			<div style="float: left;">
				{{ $relation_self->company_name }} |
				Rekeningnummer: {{ $relation_self->iban }} |
				<!-- tnv.: {{ $relation_self->iban_name }} | -->
				KVK: {{ $relation_self->kvk }} |
				BTW: {{ $relation_self->btw }}
			</div>
			<div style="float: right;">
				Pagina {{ Input::get('page') }}
			</div>
		</span>
	</span>
	<br>&nbsp;
</body>
<?php } ?>
