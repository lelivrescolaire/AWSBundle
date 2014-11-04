# Custom Identity Service - AWSBundle

Expose a Factory as identity type for configuration purposes.

## Definition Reference

```yaml
# Acme/MyBundle/Resources/config/services.yml

parameters:
    acme.custom_identity_model: Acme\MyBundle\Model\CustomModel

service:
    acme.custom_identity_type:
        class: %llsaws.identity.type.generic.factory.class%
        arguments:
            - %acme.custom_identity_model%
            - @annotation_reader
        tags:
            - {name: llsaws.identity.type, alias: custom}
```

## Parameters

`class`: Your factory (or generic factory) class name.

`arguments`:
* Your model class name. (Used by generic factory class)
* Doctrine annotation reader reference (Used by generic factory class)

`tags`:
* `name`: Identify the service as an identity type
* `alias`: The type short name for configuration.