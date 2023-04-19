interface sluggable {
    slug: string;
}

export interface Tag extends sluggable {
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

interface TranslatableString {
    [index: string]: string;
}

export interface Movie extends sluggable {
    // columns
    id: number;
    title: TranslatableString;
    product_code: string;
    release_date: string | null;
    length: number | null;
    studio_id: number | null;
    type_id: number | null;
    created_at: string;
    updated_at: string;
    love_reactant_id: number | null;
    deleted_at: string;
    // mutators
    translations: unknown;
    // relations
    reports: ContentReports;
    studio: Studio;
    type: MovieType;
    models: People;
    audits: Audits;
    tags: Tags;
    tags_translated: Tags;
    media: Media;
    love_reactant: Reactant;
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

export interface Person extends sluggable {
    // columns
    id: number;
    name: TranslatableString;
    birthdate: string | null;
    career_start: string | null;
    career_end: string | null;
    height: number | null;
    bust: number | null;
    waist: number | null;
    hip: number | null;
    blood_type: string | null;
    cup_size: string | null;
    breast_implants: boolean | null;
    country: string | null;
    created_at: string;
    updated_at: string;
    dob_doubt: boolean;
    deleted_at: string;
    // mutators
    translations: unknown;
    // relations
    reports: ContentReports;
    movies: Movies;
    audits: Audits;
    media: Media;
}
export type People = Person[];

export interface Studio extends sluggable {
    // columns
    id: number;
    name: TranslatableString;
    founded_date: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    // mutators
    translations: unknown;
    // relations
    movies: Movies;
    audits: Audits;
    media: Media;
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
    // relations
    favorites: Movies;
    wishlist: Movies;
    collection: Movies;
    audits: Audits;
    tokens: PersonalAccessTokens;
    notifications: DatabaseNotifications;
    love_reacter: Reacter;
    roles: UserRoles;
    permissions: Permissions;
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
