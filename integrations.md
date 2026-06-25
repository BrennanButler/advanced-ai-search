Integrations should be mindful of other integrations.

Indexes will no longer encourage the replacement of the record class used to generate a record for an operator. 
Now integration will need to register services that are then available in the wp-admin dashboard to be added to an index.

Core services that come packaged with the plugin will not be directly overwriteable but the data that they provide will be. This is to ensure blocks to take adavantage of the data can still be used and it's just their value that is changed.

Global overrides will be allowed for Index settings but NOT records. This will allow website owners to globally configure their post or woocommerce search indexes while maintaining robustness and integrity of the data that is used for search.


3 types of itegrations:

Record Service integration
- Filters on core services
- Registering custom services for application by the user via modifying/creating an index

Index integration
- Custom Index that is registered with the plugin so the user can use it as an Index type upon creating a new index

Block integration
- Register block integration with the plugin so the user can select an integration to use when placing the block in the editor