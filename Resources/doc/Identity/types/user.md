# User Identity Type - AWSBundle

Basic user definition to authenticate AWS API Requests.

## Configuration reference

```yaml
lls_aws:
    identities:
        my_user:
            type: user
            fields:
                key:    <your_aws_key>
                secret: <your_aws_secret>
                region: <your_aws_region>
```

## Fields

| Name       | Required | Default   | Type     | Defintition           |
| ---------- | -------- | --------- | -------- | --------------------- |
| __key__    | true     |           | _string_ | AWS API Access Key    |
| __secret__ | true     |           | _string_ | AWS API Access Secret |
| __region__ | false    | us-east-1 | _string_ | AWS API Access Region |