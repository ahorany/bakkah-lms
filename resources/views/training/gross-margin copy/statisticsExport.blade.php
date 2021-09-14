<table>
    <tr>
        <th class="">CID</th>
        <th class="">Email</th>
        <th class="">Mobile</th>
        <th class="">Name</th>
        <th class="">Course Name</th>
        <th class="">Registered At</th>
    </tr>
    @foreach($statistics as $statistic)
        <tr data-id="{{$statistic->id}}">
            <td>
                <span class="td-title">{{$statistic->id}}</span>
            </td>
            <td>
                <span class="td-title">{{$statistic->email}}</span>
            </td>
            <td>
                <span class="td-title">{{$statistic->mobile}}</span>
            </td>
            <td>
                <span class="td-title">{{ App\Helpers\Lang::TransTitle($statistic->name)}}</span>
            </td>
            <td>
                <span class="td-title">{{App\Helpers\Lang::TransTitle($statistic->title)}}</span>
            </td>
            <td>
                <span class="td-title">{{date("d-m-Y", strtotime($statistic->registered_at))}}</span>
            </td>
        </tr>
    @endforeach
</table>
