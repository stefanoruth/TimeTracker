workflow "New workflow" {
  on = "push"
  resolves = ["HTTP client"]
}

action "HTTP client" {
  uses = "swinton/httpie.action@8ab0a0e926d091e0444fcacd5eb679d2e2d4ab3d"
  runs = "https://forge.laravel.com/servers/160638/sites/743721/deploy/http?token=QBNMRPQmmzMkBFixZLCBS64uKj8sXEv3oljycmc1"
}
