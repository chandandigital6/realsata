@php
    $data = [
        ['DATE', 'DISAWAR', 'DELHI BAZAR', 'SHRI GANESH', 'FARIDABAD', 'GHAZIABAD', 'GALI'],
        ['01', '45', '08', '21', '74', '13', '91'],
        ['02', '67', '22', '55', '39', '12', '54'],
        ['03', '11', '09', '87', '42', '66', '18'],
        ['04', '90', '31', '14', '07', '29', '80'],
        ['05', '33', '71', '06', '25', '44', '19'],
    ];
@endphp

<div class="table-responsive" style="overflow-x: scroll;">
    <table width="100%" class="month_result_table rtable" border="1" cellspacing="0" cellpadding="0">
        @foreach ($data as $key => $result)
            @if ($key === 0)
                <tr>
                    @foreach ($result as $col)
                        <td style="font-size:14px; white-space:nowrap; background-color:#cc4c1a; color:#fff; text-align:center; padding:6px 2px 7px 2px; text-transform:uppercase;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $col }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    @endforeach
                </tr>
            @else
                <tr>
                    @foreach ($result as $col)
                        @if ($loop->first)
                            <td style="font-size:18px; background-color:#3333ff; color:#fff; text-align:center; font-weight:bold;">
                                {{ is_numeric($col) && $col <= 9 ? str_pad($col, 2, '0', STR_PAD_LEFT) : $col }}
                            </td>
                        @else
                            <td style="font-size:15px; font-weight:bold; background-color:#fff; padding:6px 2px 7px 2px; text-align:center;">
                                {{ !empty($col) ? (is_numeric($col) && $col <= 9 ? str_pad($col, 2, '0', STR_PAD_LEFT) : $col) : '-' }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endif
        @endforeach
    </table>
</div>