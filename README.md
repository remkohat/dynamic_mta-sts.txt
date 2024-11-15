# dynamic_mta-sts.txt

Server-wide dynamically created mta-sts.txt using PHP.

https://domain.tld/.well-known/mta-sts.txt

For Apache and Nginx.

<sup>(Based on Ubuntu 24.04 server, but should work on older versions and other distro's too)</sup>

***Why you ask?***

SMTP connections for email are more secure when the sending server supports MTA-STS and the receiving server has a MTA-STS policy in "enforced" mode.

Receiving mail: When you turn on MTA-STS for your domain, you request external mail servers to send messages to your domain only when the SMTP connection is both:
- Authenticated with a valid public certificate
- Encrypted with TLS 1.2 or higher

Mail servers that support MTA-STS will send messages to your domain only over connections that have both authentication and encryption.


***Features:***
- Fields "mode" and "max_age" according to [RFC8461](https://www.rfc-editor.org/rfc/rfc8461) can be configured
  - If values are invalid then redirect to http://<(sub.)domain.tld>/
- Check if visited URL is HTTPS, if not then redirect to HTTPS
- Check if visited URL starts with "mta-sts.", if not then redirect to http://<(sub.)domain.tld>/
- Check if (sub)domain "<(sub.)domain.tld>" has "_mta-sts.<(sub.)domain.tld>" TXT record in DNS, if not then redirect to http://<(sub.)domain.tld>/
- Check if (sub)domain "<(sub.)domain.tld>" has MX record(s) in DNS, if none then redirect to http://<(sub.)domain.tld>/
  - Generate mta-sts.txt in which MX is sorted by weight, if weight is equal then by alphabet

## _Requirements_

- Apache (with mod_rewrite enabled) or Nginx
- PHP >= 8.0

## _How To Use_

### Copy

- Copy mta-sts folder to /var/www/

  <sup>(for any other location you need to alter apache.conf or nginx.conf)</sup>

### Edit "mode" and "max_age" fields in /var/www/mta-sts/conf/[config.php](mta-sts/conf/config.php) if desired

- Default values set:
  - mode: enforce
  - max_age: 7776000 (90 days)

### Enable webserver configuration

#### _Apache_

- Copy /var/www/mta-sts/conf/[apache.conf](mta-sts/conf/apache.conf) to /etc/apache2/conf-available/mta-sts.conf
  
  ```cp /var/www/mta-sts/conf/apache.conf /etc/apache2/conf-available/mta-sts.conf```

  Or create a symlink in /etc/apache2/conf-available
  
  ```ln -s /var/www/mta-sts/conf/apache.conf /etc/apache2/conf-available/mta-sts.conf```

- Check PHP handler and change if necessary

- Enable securitytxt.conf in Apache

  ```a2enconf mta-sts```

- Reload Apache

  ```systemctl reload apache2```

#### _Nginx_

- Copy /var/www/mta-sts/conf/[nginx.conf](mta-sts/conf/nginx.conf) to /etc/nginx/snippets/mta-sts.conf
  
  ```cp /var/www/mta-sts/conf/nginx.conf /etc/nginx/snippets/mta-sts.conf```

  Or create a symlink in /etc/nginx/snippets
  
  ```ln -s /var/www/mta-sts/conf/nginx.conf /etc/nginx/snippets/mta-sts.conf```

- Check PHP handler and change if necessary

- Reload Nginx

  ```systemctl reload nginx```

### Server-wide

- Add below to every website's vhost configuration.

- If you use a management system like ISPConfig, Plesk etc. than add below to the vhost config that is used when adding or altering a website.

  Resync all websites after.

#### _Apache_

  ```RewriteEngine on```
  
  ```RewriteOptions Inherit```

#### _Nginx_

  ```include /etc/nginx/snippets/mta-sts.conf;```

## _Example output_

```
version: STSv1
mode: enforce
mx: mx1.domain.tld
mx: mx2.domain.tld
max_age: 604800
```
