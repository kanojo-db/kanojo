const Ziggy = {"url":"http:\/\/192.168.0.44","port":null,"defaults":{},"routes":{"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.update":{"uri":"reset-password","methods":["POST"]},"register":{"uri":"register","methods":["GET","HEAD"]},"verification.notice":{"uri":"email\/verify","methods":["GET","HEAD"]},"verification.verify":{"uri":"email\/verify\/{id}\/{hash}","methods":["GET","HEAD"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"user-profile-information.update":{"uri":"user\/profile-information","methods":["PUT"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"password.confirm":{"uri":"user\/confirm-password","methods":["POST"]},"horizon.stats.index":{"uri":"horizon\/api\/stats","methods":["GET","HEAD"]},"horizon.workload.index":{"uri":"horizon\/api\/workload","methods":["GET","HEAD"]},"horizon.masters.index":{"uri":"horizon\/api\/masters","methods":["GET","HEAD"]},"horizon.monitoring.index":{"uri":"horizon\/api\/monitoring","methods":["GET","HEAD"]},"horizon.monitoring.store":{"uri":"horizon\/api\/monitoring","methods":["POST"]},"horizon.monitoring-tag.paginate":{"uri":"horizon\/api\/monitoring\/{tag}","methods":["GET","HEAD"]},"horizon.monitoring-tag.destroy":{"uri":"horizon\/api\/monitoring\/{tag}","methods":["DELETE"],"wheres":{"tag":".*"}},"horizon.jobs-metrics.index":{"uri":"horizon\/api\/metrics\/jobs","methods":["GET","HEAD"]},"horizon.jobs-metrics.show":{"uri":"horizon\/api\/metrics\/jobs\/{id}","methods":["GET","HEAD"]},"horizon.queues-metrics.index":{"uri":"horizon\/api\/metrics\/queues","methods":["GET","HEAD"]},"horizon.queues-metrics.show":{"uri":"horizon\/api\/metrics\/queues\/{id}","methods":["GET","HEAD"]},"horizon.jobs-batches.index":{"uri":"horizon\/api\/batches","methods":["GET","HEAD"]},"horizon.jobs-batches.show":{"uri":"horizon\/api\/batches\/{id}","methods":["GET","HEAD"]},"horizon.jobs-batches.retry":{"uri":"horizon\/api\/batches\/retry\/{id}","methods":["POST"]},"horizon.pending-jobs.index":{"uri":"horizon\/api\/jobs\/pending","methods":["GET","HEAD"]},"horizon.completed-jobs.index":{"uri":"horizon\/api\/jobs\/completed","methods":["GET","HEAD"]},"horizon.silenced-jobs.index":{"uri":"horizon\/api\/jobs\/silenced","methods":["GET","HEAD"]},"horizon.failed-jobs.index":{"uri":"horizon\/api\/jobs\/failed","methods":["GET","HEAD"]},"horizon.failed-jobs.show":{"uri":"horizon\/api\/jobs\/failed\/{id}","methods":["GET","HEAD"]},"horizon.retry-jobs.show":{"uri":"horizon\/api\/jobs\/retry\/{id}","methods":["POST"]},"horizon.jobs.show":{"uri":"horizon\/api\/jobs\/{id}","methods":["GET","HEAD"]},"horizon.index":{"uri":"horizon\/{view?}","methods":["GET","HEAD"],"wheres":{"view":"(.*)"}},"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"telescope":{"uri":"telescope\/{view?}","methods":["GET","HEAD"],"wheres":{"view":"(.*)"}},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"debugbar.openhandler":{"uri":"_debugbar\/open","methods":["GET","HEAD"]},"debugbar.clockwork":{"uri":"_debugbar\/clockwork\/{id}","methods":["GET","HEAD"]},"debugbar.telescope":{"uri":"_debugbar\/telescope\/{id}","methods":["GET","HEAD"]},"debugbar.assets.css":{"uri":"_debugbar\/assets\/stylesheets","methods":["GET","HEAD"]},"debugbar.assets.js":{"uri":"_debugbar\/assets\/javascript","methods":["GET","HEAD"]},"debugbar.cache.delete":{"uri":"_debugbar\/cache\/{key}\/{tags?}","methods":["DELETE"]},"dusk.login":{"uri":"_dusk\/login\/{userId}\/{guard?}","methods":["GET","HEAD"]},"dusk.logout":{"uri":"_dusk\/logout\/{guard?}","methods":["GET","HEAD"]},"dusk.user":{"uri":"_dusk\/user\/{guard?}","methods":["GET","HEAD"]},"scribe":{"uri":"\/","methods":["GET","HEAD"],"domain":"developer.kanojodb.com"},"scribe.postman":{"uri":"postman","methods":["GET","HEAD"],"domain":"developer.kanojodb.com"},"scribe.openapi":{"uri":"openapi","methods":["GET","HEAD"],"domain":"developer.kanojodb.com"},"welcome":{"uri":"\/","methods":["GET","HEAD"]},"privacy":{"uri":"privacy-policy","methods":["GET","HEAD"]},"age_gate.index":{"uri":"age-check","methods":["GET","HEAD"]},"age_gate.store":{"uri":"age-check","methods":["POST"]},"plugins":{"uri":"plugins","methods":["GET","HEAD"]},"about.index":{"uri":"about","methods":["GET","HEAD"]},"settings.account":{"uri":"settings\/account","methods":["GET","HEAD"]},"settings.account.update":{"uri":"settings\/account","methods":["POST"]},"settings.sessions":{"uri":"settings\/sessions","methods":["GET","HEAD"]},"settings.tokens":{"uri":"settings\/tokens","methods":["GET","HEAD"]},"settings.tokens.create":{"uri":"settings\/tokens","methods":["POST"]},"settings.tokens.destroy":{"uri":"settings\/tokens\/{token}","methods":["DELETE"]},"notifications.index":{"uri":"notifications","methods":["GET","HEAD"]},"notifications.update":{"uri":"notifications\/{notification}","methods":["PUT","PATCH"],"bindings":{"notification":"id"}},"notifications.destroy":{"uri":"notifications\/{notification}","methods":["DELETE"],"bindings":{"notification":"id"}},"search":{"uri":"search","methods":["GET","HEAD"]},"movies.index":{"uri":"movies","methods":["GET","HEAD"]},"movies.create":{"uri":"movies\/create","methods":["GET","HEAD"]},"movies.store":{"uri":"movies","methods":["POST"]},"movies.show":{"uri":"movies\/{movie}","methods":["GET","HEAD"],"bindings":{"movie":"slug"}},"movies.edit":{"uri":"movies\/{movie}\/edit","methods":["GET","HEAD"],"bindings":{"movie":"slug"}},"movies.update":{"uri":"movies\/{movie}","methods":["PUT","PATCH"],"bindings":{"movie":"slug"}},"movies.destroy":{"uri":"movies\/{movie}","methods":["DELETE"],"bindings":{"movie":"slug"}},"movies.cast.store":{"uri":"movies\/{movie}\/cast","methods":["POST"],"bindings":{"movie":"slug"}},"movies.cast.update":{"uri":"movies\/{movie}\/cast\/{model}","methods":["PUT"],"bindings":{"movie":"slug","model":"slug"}},"movies.cast.destroy":{"uri":"movies\/{movie}\/cast\/{model}","methods":["DELETE"],"bindings":{"movie":"slug","model":"slug"}},"movies.version.store":{"uri":"movies\/{movie}\/version","methods":["POST"],"bindings":{"movie":"slug"}},"movies.version.update":{"uri":"movies\/{movie}\/version\/{version}","methods":["PUT","PATCH"],"bindings":{"movie":"slug","version":"id"}},"movies.version.destroy":{"uri":"movies\/{movie}\/version\/{version}","methods":["DELETE"],"bindings":{"movie":"slug","version":"id"}},"movies.media.index":{"uri":"movies\/{movie}\/media","methods":["GET","HEAD"],"bindings":{"movie":"slug"}},"movies.media.store":{"uri":"movies\/{movie}\/media","methods":["POST"],"bindings":{"movie":"slug"}},"movies.media.destroy":{"uri":"movies\/{movie}\/media\/{media}","methods":["DELETE"],"bindings":{"movie":"slug","media":"id"}},"movies.media.like.store":{"uri":"movies\/{movie}\/media\/{medium}\/like","methods":["POST"],"bindings":{"movie":"slug","medium":"id"}},"movies.media.dislike.store":{"uri":"movies\/{movie}\/media\/{medium}\/dislike","methods":["POST"],"bindings":{"movie":"slug","medium":"id"}},"movies.external-ids.update":{"uri":"movies\/{movie}\/external-ids","methods":["POST"],"bindings":{"movie":"slug"}},"movies.history.index":{"uri":"movies\/{movie}\/history","methods":["GET","HEAD"],"bindings":{"movie":"slug"}},"movies.like.store":{"uri":"movies\/{movie}\/like","methods":["POST"],"bindings":{"movie":"slug"}},"movies.dislike.store":{"uri":"movies\/{movie}\/dislike","methods":["POST"],"bindings":{"movie":"slug"}},"movies.favorite.store":{"uri":"movies\/{movie}\/favorite","methods":["POST"],"bindings":{"movie":"slug"}},"favorite.destroy":{"uri":"favorite\/{favorite}","methods":["DELETE"]},"movies.wishlist.store":{"uri":"movies\/{movie}\/wishlist","methods":["POST"],"bindings":{"movie":"slug"}},"wishlist.destroy":{"uri":"wishlist\/{wishlist}","methods":["DELETE"]},"movies.collection.store":{"uri":"movies\/{movie}\/collection","methods":["POST"],"bindings":{"movie":"slug"}},"collection.destroy":{"uri":"collection\/{collection}","methods":["DELETE"]},"movies.reports.index":{"uri":"movies\/{movie}\/reports","methods":["GET","HEAD"],"bindings":{"movie":"slug"}},"movies.reports.create":{"uri":"movies\/{movie}\/reports\/create","methods":["GET","HEAD"]},"movies.reports.store":{"uri":"movies\/{movie}\/reports","methods":["POST"],"bindings":{"movie":"slug"}},"movies.reports.show":{"uri":"movies\/{movie}\/reports\/{report}","methods":["GET","HEAD"]},"movies.reports.edit":{"uri":"movies\/{movie}\/reports\/{report}\/edit","methods":["GET","HEAD"]},"movies.reports.update":{"uri":"movies\/{movie}\/reports\/{report}","methods":["PUT","PATCH"],"bindings":{"movie":"slug","report":"id"}},"movies.reports.destroy":{"uri":"movies\/{movie}\/reports\/{report}","methods":["DELETE"],"bindings":{"movie":"slug","report":"id"}},"movies.preview.internal":{"uri":"movies\/{movie}\/preview","methods":["GET","HEAD"],"bindings":{"movie":"slug"}},"models.index":{"uri":"models","methods":["GET","HEAD"]},"models.create":{"uri":"models\/create","methods":["GET","HEAD"]},"models.store":{"uri":"models","methods":["POST"]},"models.show":{"uri":"models\/{model}","methods":["GET","HEAD"],"bindings":{"model":"slug"}},"models.edit":{"uri":"models\/{model}\/edit","methods":["GET","HEAD"],"bindings":{"model":"slug"}},"models.update":{"uri":"models\/{model}","methods":["PUT","PATCH"],"bindings":{"model":"slug"}},"models.destroy":{"uri":"models\/{model}","methods":["DELETE"],"bindings":{"model":"slug"}},"models.media.index":{"uri":"models\/{model}\/media","methods":["GET","HEAD"],"bindings":{"model":"slug"}},"models.media.store":{"uri":"models\/{model}\/media","methods":["POST"],"bindings":{"model":"slug"}},"models.media.destroy":{"uri":"models\/{model}\/media\/{media}","methods":["DELETE"],"bindings":{"model":"slug","media":"id"}},"models.media.like":{"uri":"models\/{model}\/media\/{media}\/like","methods":["POST"]},"models.media.dislike":{"uri":"models\/{model}\/media\/{media}\/dislike","methods":["POST"]},"models.external-ids.update":{"uri":"models\/{model}\/external-ids","methods":["POST"],"bindings":{"model":"slug"}},"models.history.index":{"uri":"models\/{model}\/history","methods":["GET","HEAD"],"bindings":{"model":"slug"}},"models.reports.index":{"uri":"models\/{model}\/reports","methods":["GET","HEAD"],"bindings":{"model":"slug"}},"models.reports.create":{"uri":"models\/{model}\/reports\/create","methods":["GET","HEAD"]},"models.reports.store":{"uri":"models\/{model}\/reports","methods":["POST"],"bindings":{"model":"slug"}},"models.reports.show":{"uri":"models\/{model}\/reports\/{report}","methods":["GET","HEAD"]},"models.reports.edit":{"uri":"models\/{model}\/reports\/{report}\/edit","methods":["GET","HEAD"]},"models.reports.update":{"uri":"models\/{model}\/reports\/{report}","methods":["PUT","PATCH"],"bindings":{"model":"slug","report":"id"}},"models.reports.destroy":{"uri":"models\/{model}\/reports\/{report}","methods":["DELETE"],"bindings":{"model":"slug","report":"id"}},"models.alternative-names.store":{"uri":"models\/{model}\/alternative-names","methods":["POST"],"bindings":{"model":"slug"}},"models.alternative-names.update":{"uri":"models\/{model}\/alternative-names\/{alternative_name}","methods":["PUT","PATCH"],"bindings":{"model":"slug"}},"models.alternative-names.destroy":{"uri":"models\/{model}\/alternative-names\/{alternative_name}","methods":["DELETE"],"bindings":{"model":"slug"}},"models.preview.internal":{"uri":"models\/{model}\/preview","methods":["GET","HEAD"],"bindings":{"model":"slug"}},"studios.index":{"uri":"studios","methods":["GET","HEAD"]},"studios.create":{"uri":"studios\/create","methods":["GET","HEAD"]},"studios.store":{"uri":"studios","methods":["POST"]},"studios.show":{"uri":"studios\/{studio}","methods":["GET","HEAD"],"bindings":{"studio":"slug"}},"studios.edit":{"uri":"studios\/{studio}\/edit","methods":["GET","HEAD"],"bindings":{"studio":"slug"}},"studios.update":{"uri":"studios\/{studio}","methods":["PUT","PATCH"],"bindings":{"studio":"slug"}},"studios.destroy":{"uri":"studios\/{studio}","methods":["DELETE"],"bindings":{"studio":"slug"}},"studios.media.index":{"uri":"studios\/{studio}\/media","methods":["GET","HEAD"],"bindings":{"studio":"slug"}},"studios.media.store":{"uri":"studios\/{studio}\/media","methods":["POST"],"bindings":{"studio":"slug"}},"studios.media.destroy":{"uri":"studios\/{studio}\/media\/{media}","methods":["DELETE"],"bindings":{"studio":"slug","media":"id"}},"studios.history.index":{"uri":"studios\/{studio}\/history","methods":["GET","HEAD"],"bindings":{"studio":"slug"}},"studios.reports.index":{"uri":"studios\/{studio}\/reports","methods":["GET","HEAD"],"bindings":{"studio":"slug"}},"studios.reports.create":{"uri":"studios\/{studio}\/reports\/create","methods":["GET","HEAD"]},"studios.reports.store":{"uri":"studios\/{studio}\/reports","methods":["POST"],"bindings":{"studio":"slug"}},"studios.reports.show":{"uri":"studios\/{studio}\/reports\/{report}","methods":["GET","HEAD"]},"studios.reports.edit":{"uri":"studios\/{studio}\/reports\/{report}\/edit","methods":["GET","HEAD"]},"studios.reports.update":{"uri":"studios\/{studio}\/reports\/{report}","methods":["PUT","PATCH"],"bindings":{"studio":"slug","report":"id"}},"studios.reports.destroy":{"uri":"studios\/{studio}\/reports\/{report}","methods":["DELETE"],"bindings":{"studio":"slug","report":"id"}},"studios.external-ids.update":{"uri":"studios\/{studio}\/external-ids","methods":["POST"],"bindings":{"studio":"slug"}},"series.index":{"uri":"series","methods":["GET","HEAD"]},"series.create":{"uri":"series\/create","methods":["GET","HEAD"]},"series.store":{"uri":"series","methods":["POST"]},"series.show":{"uri":"series\/{series}","methods":["GET","HEAD"],"bindings":{"series":"slug"}},"series.edit":{"uri":"series\/{series}\/edit","methods":["GET","HEAD"],"bindings":{"series":"slug"}},"series.update":{"uri":"series\/{series}","methods":["PUT","PATCH"],"bindings":{"series":"slug"}},"series.destroy":{"uri":"series\/{series}","methods":["DELETE"],"bindings":{"series":"slug"}},"series.history.index":{"uri":"series\/{series}\/history","methods":["GET","HEAD"],"bindings":{"series":"slug"}},"series.reports.index":{"uri":"series\/{series}\/reports","methods":["GET","HEAD"],"bindings":{"series":"slug"}},"series.reports.create":{"uri":"series\/{series}\/reports\/create","methods":["GET","HEAD"]},"series.reports.store":{"uri":"series\/{series}\/reports","methods":["POST"],"bindings":{"series":"slug"}},"series.reports.show":{"uri":"series\/{series}\/reports\/{report}","methods":["GET","HEAD"]},"series.reports.edit":{"uri":"series\/{series}\/reports\/{report}\/edit","methods":["GET","HEAD"]},"series.reports.update":{"uri":"series\/{series}\/reports\/{report}","methods":["PUT","PATCH"],"bindings":{"series":"slug","report":"id"}},"series.reports.destroy":{"uri":"series\/{series}\/reports\/{report}","methods":["DELETE"],"bindings":{"series":"slug","report":"id"}},"profile.show":{"uri":"user\/{user}","methods":["GET","HEAD"],"bindings":{"user":"id"}},"user.locale.store":{"uri":"user\/locale","methods":["POST"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };