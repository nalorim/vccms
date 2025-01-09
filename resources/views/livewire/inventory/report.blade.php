<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div wire:ignore class="bg-slate-900 rounded-lg p-4">
        <div id="chart" class="h-[330px]"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>

        // const daysInMonth = $wire.daysInMonth;

        var options = {
            chart: {
                type: 'line'
            },
            series: [{
                name: 'sales',
                data: [1,2,3,1,4]
            }],
            xaxis: {
                categories: ['a', 'b', 'c', 'd', 'e']
            },
        }
        
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        
        chart.render();
    </script>

</div>
