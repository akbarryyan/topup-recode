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
                  <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Berita</h4>
                  </div>
                  <div class="card-body">
                    {{ number_format($stats['total_news']) }}
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
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Statistics</h4>
                  <div class="card-header-action">
                    <div class="btn-group">
                      <a href="#" class="btn btn-primary">Week</a>
                      <a href="#" class="btn">Month</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="myChart" height="182"></canvas>
                  <div class="statistic-details mt-sm-4">
                    <div class="statistic-details-item">
                      <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 7%</span>
                      <div class="detail-value">$243</div>
                      <div class="detail-name">Today's Sales</div>
                    </div>
                    <div class="statistic-details-item">
                      <span class="text-muted"><span class="text-danger"><i class="fas fa-caret-down"></i></span> 23%</span>
                      <div class="detail-value">$2,902</div>
                      <div class="detail-name">This Week's Sales</div>
                    </div>
                    <div class="statistic-details-item">
                      <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span>9%</span>
                      <div class="detail-value">$12,821</div>
                      <div class="detail-name">This Month's Sales</div>
                    </div>
                    <div class="statistic-details-item">
                      <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 19%</span>
                      <div class="detail-value">$92,142</div>
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
@endsection