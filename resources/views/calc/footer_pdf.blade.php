<?php

use \Calctool\Models\Relation;
use \Calctool\Models\User;
use \Calctool\Models\Iban;

$relation_self = Relation::find(User::find(Input::get('uid'))->self_id);
if ($relation_self) {
?>
	<span style="color:#696969;">
		<span style="text-align: center;">
			<span style="font-family:arial,helvetica,sans-serif;">
				<span style="font-size:10px;">
					{{ $relation_self->company_name }} |
					Rekeningnummer: {{ Iban::where('relation_id','=',$relation_self->id)->first()['iban'] }} |
					tnv.: {{ Iban::where('relation_id','=',$relation_self->id)->first()['iban_name'] }} |
					KVK: {{ $relation_self->kvk }} |
					BTW: {{ $relation_self->btw }}
				</span>
			</span>
		</span>
	</span>
	<br>&nbsp;
<?php } ?>
