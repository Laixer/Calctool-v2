<h3>Omschrijving werkzaamheden</h3>

<h4>Onderaanneming</h4>

<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc">Onderdeel</th>
        <th class="unit">Werkzaamheid</th>
        <th class="qty">Uren</th>
        <th class="qty">Arbeid</th>
        <th class="qty">Materiaal</th>
        <th class="qty">Overig</th>
        <th class="total">Totaal</th>
    </tr>
</thead>
<tbody>
    @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
    @foreach ($chapter->activities()->where('part_id',\BynqIO\Dynq\Models\Part::where('part_name','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
    <tr>
        <td class="desc">{{ $chapter->chapter_name }}</td>
        <td class="subject">{{ $activity->activity_name }}</td>
        <td class="qty">@money(\BynqIO\Dynq\Calculus\CalculationOverview::laborTotal($activity))</td>
        <td class="qty">@money(\BynqIO\Dynq\Calculus\CalculationOverview::laborActivity($project->hour_rate, $activity))</td>
        <td class="qty">@money(\BynqIO\Dynq\Calculus\CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat))</td>
        <td class="qty">@money(\BynqIO\Dynq\Calculus\CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip))</td>
        <td class="total">@money(\BynqIO\Dynq\Calculus\CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))</td>
    </tr>
    @endforeach
    @endforeach
</tbody>
</table>
