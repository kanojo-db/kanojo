export interface Country {
    id: number;
    name: string;
}

export type Countries = Country[];

export interface Gender {
    id: number;
    name: string;
}

export type Genders = Gender[];

export interface Notification {
    id: string;
    type: string;
    notifiable_type: string;
    notifiable_id: number;
    data: {
        [key: string]: unknown;
    };
    read_at: string | null;
    created_at: string;
    updated_at: string;
}

export type Notifications = Notification[];

export interface Linkable {
    external_links: Record<string, string>;
}

interface ReactionCounter {
    count: number;
    created_at: string;
    id: number;
    reactant_id: number;
    reactant_type_id: number;
    updated_at: string;
    weight: number;
}

type ReactionCounters = ReactionCounter[];

interface Reacter {
    id: string;
    type: string;
    reacterable: User;
    created_at: string;
    updated_at: string;
}

interface ReactionType {
    id: string;
    name: string;
    mass: number;
    created_at: string;
    updated_at: string;
}

interface Reaction {
    id: string;
    rate: number;
    reactant_id: number;
    reacter: Reacter;
    reacter_id: number;
    reaction_type_id: number;
    type: ReactionType;
    created_at: string;
    updated_at: string;
}

type Reactions = Reaction[];

interface Sluggable {
    slug: string;
}

interface Shareable {
    social_image: string;
}

export interface Reactable {
    love_reactant_id: number;
    love_reactant: {
        id: number;
        reaction_counters: ReactionCounters;
        reaction_total: {
            count: number;
            created_at: string;
            id: number;
            reactant_id: number;
            updated_at: string;
            weight: number;
        };
        reactions: Reactions;
        type: string;
        created_at: string;
        updated_at: string;
    };
}

export interface Tag extends Sluggable {
    id: number;
    name: TranslatableString;
    type: string;
    order_column: number;
    created_at: string;
    updated_at: string;
}

export type Tags = Tag[];

export interface ContentReport {
    // columns
    id: number;
    reporter_id: number;
    reportable_id: number;
    reportable_type: string;
    type: string;
    message: string;
    public: boolean;
    resolved: boolean;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    // relations
    reportable: ContentReport;
    reporter: User;
}
export type ContentReports = ContentReport[];

type TranslatableString = Record<string, string>;

export interface Media extends Reactable {
    collection_name: string;
    conversions_disk: string;
    created_at: string;
    custom_properties: {
        width: number;
        height: number;
    };
    disk: string;
    file_name: string;
    generated_conversions: Array<string>;
    id: number;
    manipulations: Array<string>;
    mime_type: string;
    model_id: number;
    model_type: string;
    name: string;
    order_column: number;
    original_url: string;
    preview_url: string;
    responsive_images: Array<string>;
    size: number;
    updated_at: string;
    uuid: string;
}

export type Medias = Media[];

export interface Series extends Sluggable, Linkable {
    // columns
    id: number;
    title: TranslatableString;
    slug: string;
    studio_id?: number;
    created_at: string;
    updated_at: string;
    // relations
    studio: Studio;
    movies: Paginated<Movie>;
    audits?: Audits;
    // counts
    movies_count?: number;
}

export interface MovieVersion {
    id: number;
    format: string;
    product_code: string;
    release_date: string;
    barcode: string;
    created_at: string;
    updated_at: string;
}

export type MovieVersions = MovieVersion[];

export interface Movie extends Sluggable, Reactable, Linkable, Shareable {
    // columns
    id: number;
    title: TranslatableString;
    barcode: string | null;
    release_date: string | null;
    length: number | null;
    studio_id: number | null;
    type_id: number | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    // mutators
    translations: unknown;
    // relations
    reports?: ContentReports | null;
    studio?: Studio | null;
    series?: Series | null;
    type: MovieType;
    models?: (People & MoviePersonPivot[]) | null;
    media: Medias;
    in_favorites: boolean;
    in_wishlist: boolean;
    in_collection: boolean;
    audits?: Audits | null;
    versions?: MovieVersions | null;
    // accessors
    poster: Media | null;
    fanart: Media | null;
    poster_count: number;
    fanart_count: number;
    // feature flags
    is_vr: boolean;
    is_3d: boolean;
    // external ids
    imdb_id: string | null;
    tmdb_id: string | null;
    fanza_id: string | null;
    mgstage_id: string | null;
    dmm_id: string | null;
    fc2_id: string | null;
    wikidata_id: string | null;
    google_id: string | null;
}
export type Movies = Movie[];

export interface MovieType {
    // columns
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
    // relations
    movies: Movies;
}
export type MovieTypes = MovieType[];

export interface PersonAlias {
    // columns
    id: number;
    alias: string;
    person_id: number;
    locked: boolean;
    created_at: string;
    updated_at: string;
}

export type PersonAliases = PersonAlias[];

export interface Person extends Sluggable, Linkable, Shareable {
    // columns
    id: number;
    name: TranslatableString;
    gender: Gender | null;
    birthdate: string | null;
    country: Country | null;
    career_start: string | null;
    career_end: string | null;
    height: number | null;
    bust: number | null;
    waist: number | null;
    hip: number | null;
    blood_type: string | null;
    cup_size: string | null;
    breast_implants?: boolean | null;
    created_at: string;
    updated_at: string;
    dob_doubt: boolean;
    deleted_at: string;
    twitter_id: string | null;
    instagram_id: string | null;
    tiktok_id: string | null;
    ameblo_id: string | null;
    wikidata_id: string | null;
    youtube_id: string | null;
    google_id: string | null;
    imdb_id: string | null;
    fanza_id: string | null;
    tmdb_id: string | null;
    line_blog_id: string | null;
    onlyfans_id: string | null;
    // mutators
    translations: unknown;
    // relations
    reports: ContentReports;
    movies: Paginated<Movie>;
    media: Medias;
    aliases: PersonAliases;
    audits?: Audits;
    // accessors
    poster?: Media;
    poster_count: number;
}

export type People = Person[];

export interface Studio extends Sluggable, Linkable {
    // columns
    id: number;
    name: TranslatableString;
    founded_date: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    // mutators
    translations: unknown;
    audits?: Audits;
    // accessors
    logo: Media | null;
    logo_count: number;
    // relations
    movies: Paginated<Movie>;
    media: Medias;
    models: Persons;
    series: Series[];
}

export type Studios = Studio[];

export interface User {
    // columns
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    password?: string;
    two_factor_secret?: string | null;
    two_factor_recovery_codes?: string | null;
    two_factor_confirmed_at: string | null;
    remember_token?: string | null;
    current_team_id: number | null;
    profile_photo_path: string | null;
    created_at: string;
    updated_at: string;
    love_reacter_id: number | null;
    deleted_at: string;
    unread_notifications_count: number | null;
    // relations
    favorites: Movies;
    wishlist: Movies;
    collection: Movies;
    roles: UserRoles;
    permissions: Permissions;
    is_administrator: boolean;
    is_banned: boolean;
    audits_this_week: number;
    total_audits: number;
    has_gravatar: boolean;
    gravatar_url: string;
}
export type Users = User[];

export interface UserRole {
    name: string;
    created_at: string;
    guard_name: string;
    id: number;
    pivot: {
        model_id: number;
        model_type: string;
        role_id: number;
    };
    updated_at: string;
}
export type UserRoles = UserRole[];

export interface PaginationLink {
    active: boolean;
    label: string;
    url?: string;
}

export interface Paginated<T = unknown> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export type Item = Movie | Person | Studio | Series;

export type Items = Item[];

export interface MoviePersonPivot {
    pivot: {
        id: number;
        movie_id: number;
        person_id: number;
        locked: boolean;
        age: number;
        created_at: string;
        updated_at: string;
    };
}

export interface Audit {
    id: number;
    user_type: string;
    user_id: number;
    event: string;
    auditable_type: string;
    auditable_id: number;
    auditable: Item;
    old_values: string;
    new_values: string;
    url: string;
    ip_address: string;
    user_agent: string;
    tags: string;
    created_at: string;
    updated_at: string;
}

export type Audits = Audit[];

export interface Token {
    id: number;
    tokenable_type: string;
    tokenable_id: number;
    name: string;
    token: string;
    abilities: string;
    last_used_at: string;
    expires_at: string;
    created_at: string;
    updated_at: string;
}

export type Tokens = Token[];

export interface Session {
    id: number;
    user_id: number;
    ip_address: string;
    user_agent: string;
    payload: string;
    is_current_device: boolean;
    browser: string;
    platform: string;
    last_active: string;
}

export type Sessions = Session[];
