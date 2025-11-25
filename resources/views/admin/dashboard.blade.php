@extends('admin.layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>

          @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden=\"true\">&times;</span>
            </button>
          </div>
          @endif

          @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden=\"true\">&times;</span>
            </button>
          </div>
          @endif
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Users</h4>
                  </div>
                  <div class="card-body">
                    {{ number_format($stats['total_users']) }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-credit-card"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Payment Channel</h4>
                  </div>
                  <div class="card-body">
                    {{ number_format($stats['total_payment_methods']) }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Layanan Pulsa & PPOB</h4>
                  </div>
                  <div class="card-body">
                    {{ number_format($stats['total_prepaid_services']) }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-gamepad"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Layanan Game</h4>
                  </div>
                  <div class="card-body">
                    {{ number_format($stats['total_game_services']) }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                  <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Visitors</h4>
                  </div>
                  <div class="card-body">
                    {{ number_format($totalVisitors) }}
                  </div>
                </div>
              </div>
            </div>                  
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Statistics (Last 30 Days)</h4>
                </div>
                <div class="card-body">
                  <div style="height: 300px; position: relative;">
                    <canvas id="myChart"></canvas>
                  </div>
                  <div class="statistic-details mt-sm-4">
                    <div class="statistic-details-item">
                      <span class="text-muted">
                        <span class="text-{{ $salesStats['today']['change'] >= 0 ? 'primary' : 'danger' }}">
                          <i class="fas fa-caret-{{ $salesStats['today']['change'] >= 0 ? 'up' : 'down' }}"></i>
                        </span> 
                        {{ abs($salesStats['today']['change']) }}%
                      </span>
                      <div class="detail-value">Rp {{ number_format($salesStats['today']['amount'], 0, ',', '.') }}</div>
                      <div class="detail-name">Today's Sales</div>
                    </div>
                    <div class="statistic-details-item">
                      <span class="text-muted">
                        <span class="text-{{ $salesStats['week']['change'] >= 0 ? 'primary' : 'danger' }}">
                          <i class="fas fa-caret-{{ $salesStats['week']['change'] >= 0 ? 'up' : 'down' }}"></i>
                        </span> 
                        {{ abs($salesStats['week']['change']) }}%
                      </span>
                      <div class="detail-value">Rp {{ number_format($salesStats['week']['amount'], 0, ',', '.') }}</div>
                      <div class="detail-name">This Week's Sales</div>
                    </div>
                    <div class="statistic-details-item">
                      <span class="text-muted">
                        <span class="text-{{ $salesStats['month']['change'] >= 0 ? 'primary' : 'danger' }}">
                          <i class="fas fa-caret-{{ $salesStats['month']['change'] >= 0 ? 'up' : 'down' }}"></i>
                        </span>
                        {{ abs($salesStats['month']['change']) }}%
                      </span>
                      <div class="detail-value">Rp {{ number_format($salesStats['month']['amount'], 0, ',', '.') }}</div>
                      <div class="detail-name">This Month's Sales</div>
                    </div>
                    <div class="statistic-details-item">
                      <span class="text-muted">
                        <span class="text-{{ $salesStats['year']['change'] >= 0 ? 'primary' : 'danger' }}">
                          <i class="fas fa-caret-{{ $salesStats['year']['change'] >= 0 ? 'up' : 'down' }}"></i>
                        </span> 
                        {{ abs($salesStats['year']['change']) }}%
                      </span>
                      <div class="detail-value">Rp {{ number_format($salesStats['year']['amount'], 0, ',', '.') }}</div>
                      <div class="detail-name">This Year's Sales</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Recent Activities</h4>
                </div>
                <div class="card-body">             
                  <ul class="list-unstyled list-unstyled-border">
                    @forelse($recentActivities as $activity)
                      <li class="media">
                        <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center text-white {{ $activity['icon_bg'] ?? 'bg-secondary' }}" style="width:50px;height:50px;">
                          <i class="{{ $activity['icon'] ?? 'fas fa-info-circle' }}"></i>
                        </div>
                        <div class="media-body">
                          <div class="float-right text-muted">{{ optional($activity['timestamp'])->diffForHumans() }}</div>
                          <div class="media-title">{{ $activity['title'] }}</div>
                          <span class="text-small text-muted">{{ $activity['subtitle'] }}</span>
                        </div>
                      </li>
                    @empty
                      <li class="media">
                        <div class="media-body text-center text-muted">
                          Belum ada aktivitas terbaru.
                        </div>
                      </li>
                    @endforelse
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Visitor Statistics (Last 30 Days)</h4>
                </div>
                <div class="card-body">
                  <div style="height: 300px; position: relative;">
                    <canvas id="visitorChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4>Game Paling Laris</h4>
                </div>
                <div class="card-body">
                  @if($topGameServices->isEmpty())
                    <p class="text-muted mb-0">Belum ada transaksi game.</p>
                  @else
                    <div class="table-responsive">
                      <table class="table table-striped mb-0">
                        <thead>
                          <tr>
                            <th>Layanan Game</th>
                            <th class="text-right">Total Order</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($topGameServices as $service)
                            <tr>
                              <td>{{ $service->service_name }}</td>
                              <td class="text-right"><span class="badge badge-success">{{ $service->total_orders }}</span></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4>Pulsa & PPOB Paling Laris</h4>
                </div>
                <div class="card-body">
                  @if($topPrepaidServices->isEmpty())
                    <p class="text-muted mb-0">Belum ada transaksi pulsa/PPOB.</p>
                  @else
                    <div class="table-responsive">
                      <table class="table table-striped mb-0">
                        <thead>
                          <tr>
                            <th>Layanan</th>
                            <th class="text-right">Total Order</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($topPrepaidServices as $service)
                            <tr>
                              <td>{{ $service->service_name }}</td>
                              <td class="text-right"><span class="badge badge-warning">{{ $service->total_orders }}</span></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </section>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Debug: Check if data exists
  var chartLabels = {!! json_encode($chartLabels) !!};
  var gameData = {!! json_encode($chartGameData) !!};
  var prepaidData = {!! json_encode($chartPrepaidData) !!};
  
  console.log('Chart Labels:', chartLabels);
  console.log('Game Data:', gameData);
  console.log('Prepaid Data:', prepaidData);
  
  // Check if all values are zero
  var gameTotalValue = gameData.reduce((a, b) => a + b, 0);
  var prepaidTotalValue = prepaidData.reduce((a, b) => a + b, 0);
  console.log('Total Game Sales (30 days):', gameTotalValue);
  console.log('Total Prepaid Sales (30 days):', prepaidTotalValue);
  
  if (gameTotalValue === 0 && prepaidTotalValue === 0) {
    console.warn('⚠️ No transaction data in the last 30 days!');
  }

  // Check if Chart.js is loaded
  if (typeof Chart === 'undefined') {
    console.error('Chart.js is not loaded!');
    return;
  }

  // Chart for Statistics - Last 7 Days Sales
  var ctx = document.getElementById("myChart");
  if (!ctx) {
    console.error('Canvas element not found!');
    return;
  }

  var myChart = new Chart(ctx.getContext('2d'), {
    type: 'line',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Game',
        data: {!! json_encode($chartGameData) !!},
        borderWidth: 2,
        backgroundColor: 'rgba(99, 179, 237, 0.1)',
        borderColor: '#63b3ed',
        pointBackgroundColor: '#63b3ed',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        fill: true
      }, {
        label: 'Pulsa & PPOB',
        data: {!! json_encode($chartPrepaidData) !!},
        borderWidth: 2,
        backgroundColor: 'rgba(252, 211, 77, 0.1)',
        borderColor: '#fcd34d',
        pointBackgroundColor: '#fcd34d',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: true,
        position: 'top',
        labels: {
          padding: 10,
          usePointStyle: true
        }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            callback: function(value) {
              if (value >= 1000000) {
                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
              } else if (value >= 1000) {
                return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
              }
              return 'Rp ' + value;
            }
          },
          gridLines: {
            drawBorder: false,
            color: '#f3f4f6'
          }
        }],
        xAxes: [{
          gridLines: {
            display: false
          }
        }]
      },
      tooltips: {
        backgroundColor: '#1f2937',
        titleFontSize: 13,
        bodyFontSize: 12,
        xPadding: 10,
        yPadding: 10,
        displayColors: true,
        callbacks: {
          label: function(tooltipItem, data) {
            var label = data.datasets[tooltipItem.datasetIndex].label || '';
            var value = tooltipItem.yLabel;
            return label + ': Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
        }
      }
    }
  });

  console.log('Chart initialized successfully!');
  // Visitor Chart
  // Visitor Chart
  var visitorCtx = document.getElementById('visitorChart').getContext('2d');
  var visitorChart = new Chart(visitorCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($visitorChartLabels) !!},
      datasets: [{
        label: 'Visitors',
        data: {!! json_encode($visitorChartData) !!},
        borderWidth: 2,
        backgroundColor: 'rgba(103, 119, 239, 0.1)',
        borderColor: '#6777ef',
        pointBackgroundColor: '#6777ef',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            stepSize: 1,
            callback: function(value) {
              if (value % 1 === 0) {
                return value;
              }
            }
          },
          gridLines: {
            drawBorder: false,
            color: '#f3f4f6'
          }
        }],
        xAxes: [{
          gridLines: {
            display: false
          }
        }]
      },
      tooltips: {
        backgroundColor: '#1f2937',
        titleFontSize: 13,
        bodyFontSize: 12,
        xPadding: 10,
        yPadding: 10,
        displayColors: false,
        callbacks: {
          label: function(tooltipItem, data) {
            return tooltipItem.yLabel + ' Visitors';
          }
        }
      }
    }
  });
});
</script>
@endpush

@endsection