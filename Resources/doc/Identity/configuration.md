# Identity - Configuration Reference - AWSBundle

You can create multiple indentities using the following configuration:

```yaml
lls_aws:
    identities:
        lls_user:               # Arbirary identity name
            type:   user            # Identity type
            fields:                 # Identity informations
                key:    %aws_key%       # AWS API Access Key
                secret: %aws_secret%    # AWS API Access secret
```