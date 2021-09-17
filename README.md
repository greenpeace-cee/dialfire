# dialfire

CiviCRM extension to integrate [dialfire](https://www.dialfire.com/) with CiviCRM.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.2+
* CiviCRM 5.39+

## Installation (Web UI)

Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl dialfire@https://github.com/greenpeace-cee/dialfire/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/greenpeace-cee/dialfire.git
cv en dialfire
```

## Configuration

### Tenant

First, create a `DialfireTenant` with the identifier and API token of your dialfire account. Refer to dialfire's documentation
on how to obtain these values and then create the tenant as follows:

    cv api4 DialfireTenant.create +v dialfire_tenant_identifier=xyz12345 +v name=MyOrganization +v token=secret

### Fields

The extension comes with a `dialfire_default_contact_query` and `dialfire_default_field_map` setting. These settings
are used as a global default which may be overwritten for individual dialfire campaigns via the `DialfireCampaign`
entity using the `contact_query` and `field_map` parameters

`dialfire_default_contact_query` allows you to define an arbitrary API4 request to the `DialfireContact.get` action to
configure which entities and fields should be available to be exported to dialfire. The default value allows you to
use all fields of the `DialfireContact` and `Contact` API4 entities, all fields of the primary address and any emails
or phones associated with the contact. Alternative queries could be built using CiviCRM's API4 Explorer.

`dialfire_default_field_map` is a key-value map. The key is the field name in dialfire, and the value is a [JMESPath](https://jmespath.org/)
expression that will be evaluated against the result of the contact query defined above.

#### Example

##### Query (`dialfire_default_contact_query`):

```json
{
  "select":["*","contact.*","address.*"],
  "join":[["Contact AS contact","INNER",null,["contact_id","=","contact.id"]]],
  "chain":{"email":["Email","get",{"where":[["contact_id","=","$contact_id"]],"orderBy":{"is_primary":"DESC","id":"DESC"}}]}
}
```

##### Field map (`dialfire_default_field_map`):

```json
{
  "first_name": "\"contact.first_name\"",
  "email1": "email[0].email",
  "email2": "email[1].email"
}
```

> :bulb: JMESPath expressions referencing fields containing a `.` symbol must be surrounded by double quotes.

##### API result (as returned by the `DialfireContact.get` API4 request defined in `dialfire_default_contact_query`):

```json
{
  "id": 1,
  "dialfire_task_id": 1,
  "contact_id": 2,
  "contact.first_name": "Sanford",
  "email": [
    {
      "email": "foo@example.com"
    },
    {
      "email": "bar@example.com"
    }
  ],
}
```

##### Resulting export to dialfire:

```json
{
  "first_name": "Sanford",
  "email1": "foo@example.com",
  "email2": "bar@example.com"
}
```
