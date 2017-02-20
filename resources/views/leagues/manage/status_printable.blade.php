<h1 style="text-align: center">{{$league->displayName()}}<h1>

<table cellspacing="0" border="1" style="width:100%;">
  <thead>
    <tr>
      <th style="width:15%;padding:15px;background-color:#ddd;">Player</th>
      <th style="width:15%;padding:15px;background-color:#ddd;">Team</th>
      <th style="width:5%;padding:15px;background-color:#ddd;">Paid?</th>
      <th style="width:5%;padding:15px;background-color:#ddd;">Waiver?</th>
      <th style="width:10%;padding:15px;background-color:#ddd;">Owed</th>
      <th style="width:10%;padding:15px;background-color:#ddd;">Shirt</th>
      <th style="width:10%;padding:15px;background-color:#ddd;">Disc</th>
      <th style="width:10%;padding:15px;background-color:#ddd;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th style="width:10%;padding:15px;background-color:#ddd;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th style="width:10%;padding:15px;background-color:#ddd;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    @foreach($statuses as $status)
      <tr>
        <td style="width:15%;padding:15px;text-align:left;color: #888;">{{ $status['first_name'] . ' '  . $status['last_name'] }}</td>
        <td style="width:15%;padding:15px;text-align:left;color: #888;">{{ $status['team_name'] }}</td>
        <td style="width:5%;padding:15px;text-align:center;">{!! ($status['paid']) ? '<span style="color: #888;">Yes</span>' : '<strong>No</strong>'!!}</td>
        <td style="width:5%;padding:15px;text-align:center;">{!! ($status['waiver']) ? '<span style="color: #888;">Yes</span>' : '<strong>No</strong>'!!}</td>
        <td style="width:10%;padding:15px;text-align:center;">{!! ($status['balance']) ? '<strong>$'.$status['balance'].'</strong>' : '<span style="color: #888;">0</span>' !!}</td>
        <td style="width:10%;padding:15px;text-align:center;">&nbsp;</td>
        <td style="width:10%;padding:15px;text-align:center;">&nbsp;</td>
        <td style="width:10%;padding:15px;text-align:center;">&nbsp;</td>
        <td style="width:10%;padding:15px;text-align:center;">&nbsp;</td>
        <td style="width:10%;padding:15px;text-align:center;">&nbsp;</td>
      </tr>
    @endforeach
  </tbody>
</table>