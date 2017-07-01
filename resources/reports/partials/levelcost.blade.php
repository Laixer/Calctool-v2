<h3>Totaalkosten per Werkzaamheid</h3>

@isset($separate_subcon)
<h4>Aanneming</h4>

<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc">Onderdeel</th>
        <th class="unit">Werkzaamheid</th>
        <th class="total">Totaal</th>
    </tr>
</thead>
<tbody>
    @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
    @foreach ($chapter->activities()->where('part_id',\BynqIO\Dynq\Models\Part::where('part_name','contracting')->first()->id)->orderBy('priority')->get() as $activity)
    <tr>
        <td class="desc" @if(0) style="background:white;" @endif >{{ $chapter->chapter_name }}</td>
        <td class="subject">{{ $activity->activity_name }}</td>
        <td class="total">@money(\BynqIO\Dynq\Calculus\CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))</td>
    </tr>
    @endforeach
    @endforeach
</tbody>
</table>

<h4>Onderaanneming</h4>

<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc">Onderdeel</th>
        <th class="unit">Werkzaamheid</th>
        <th class="total">Totaal</th>
    </tr>
</thead>
<tbody>
    @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
    @foreach ($chapter->activities()->where('part_id',\BynqIO\Dynq\Models\Part::where('part_name','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
    <tr>
        <td class="desc" @if(0) style="background:white;" @endif >{{ $chapter->chapter_name }}</td>
        <td class="subject">{{ $activity->activity_name }}</td>
        <td class="total">@money(\BynqIO\Dynq\Calculus\CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))</td>
    </tr>
    @endforeach
    @endforeach
</tbody>
</table>
@else
<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc">Onderdeel</th>
        <th class="unit">Werkzaamheid</th>
        <th class="total">Totaal</th>
    </tr>
</thead>
<tbody>
    @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
    @foreach ($chapter->activities()->orderBy('priority')->get() as $activity)
    <tr>
        <td class="desc" @if(0) style="background:white;" @endif >{{ $chapter->chapter_name }}</td>
        <td class="subject">{{ $activity->activity_name }}</td>
        <td class="total">@money(\BynqIO\Dynq\Calculus\CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))</td>
    </tr>
    @endforeach
    @endforeach
</tbody>
</table>
@endif
