# gnusocial_ynh

[![Integration level](https://dash.yunohost.org/integration/gnusocial.svg)](https://dash.yunohost.org/appci/app/gnusocial) ![](https://ci-apps.yunohost.org/ci/badges/gnusocial.status.svg) ![](https://ci-apps.yunohost.org/ci/badges/gnusocial.maintain.svg)

This app brings [GNU social](https://gnu.io/social/) with [Qvitter](https://git.gnu.io/h2p/Qvitter) plugin.

Before install this app you must have:

- A subdomain as ```social.example.com``` or ```gs.example.com```. Qvitter plugin won't work with paths such as ```example.com/gs```
- Created certificates from ```Let's encrypt``` or any non self-signed certificates

There are several profiles for a GNU Social node:

- Public:  Registration for everybody is enabled.
- Community: Ideal for a smalls groups. No open registration available.
- Private: Nobody outside could see the node.

Be patient. So far this installation doesn't do the following:

- Single Sign On


