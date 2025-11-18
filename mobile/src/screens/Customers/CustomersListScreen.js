/**
 * Customers List Screen
 */

import React, {useState, useEffect} from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
  TextInput,
  RefreshControl,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import {customersApi} from '../../services/api';
import {colors, typography, spacing, borderRadius, shadows} from '../../utils/theme';

const CustomersListScreen = ({navigation}) => {
  const [customers, setCustomers] = useState([]);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [filter, setFilter] = useState('all');

  useEffect(() => {
    loadCustomers();
  }, [filter]);

  const loadCustomers = async () => {
    try {
      const params = {};
      if (filter === 'vip') params.vip_only = true;
      if (filter === 'at_risk') params.at_risk_only = true;

      // Mock data for demo
      const mockCustomers = [
        {
          id: '1',
          full_name: 'Jean Dupont',
          email: 'jean.dupont@email.com',
          tier: 'vip',
          visit_count: 24,
          lifetime_value: '1,250',
          days_since_last_visit: 3,
        },
        {
          id: '2',
          full_name: 'Marie Martin',
          email: 'marie.martin@email.com',
          tier: 'super_vip',
          visit_count: 42,
          lifetime_value: '2,890',
          days_since_last_visit: 1,
        },
        {
          id: '3',
          full_name: 'Pierre Durand',
          email: 'pierre.durand@email.com',
          tier: 'regular',
          visit_count: 8,
          lifetime_value: '456',
          days_since_last_visit: 67,
          at_risk: true,
        },
      ];

      setCustomers(mockCustomers);
    } catch (error) {
      console.error('Error loading customers:', error);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadCustomers();
    setRefreshing(false);
  };

  const getTierBadge = tier => {
    switch (tier) {
      case 'super_vip':
        return {label: '⭐ Super VIP', colors: colors.gradient.success};
      case 'vip':
        return {label: '👑 VIP', colors: colors.gradient.warning};
      default:
        return {label: 'Regular', colors: [colors.gray, colors.gray]};
    }
  };

  const renderCustomerCard = ({item}) => {
    const tierBadge = getTierBadge(item.tier);

    return (
      <TouchableOpacity
        style={[styles.customerCard, item.at_risk && styles.atRiskCard]}
        onPress={() => navigation.navigate('CustomerDetail', {customerId: item.id})}>
        <View style={styles.customerHeader}>
          <View style={styles.avatar}>
            <Text style={styles.avatarText}>
              {item.full_name.split(' ').map(n => n[0]).join('')}
            </Text>
          </View>
          <View style={styles.customerInfo}>
            <Text style={styles.customerName}>{item.full_name}</Text>
            <Text style={styles.customerEmail}>{item.email}</Text>
            <LinearGradient
              colors={tierBadge.colors}
              style={styles.tierBadge}
              start={{x: 0, y: 0}}
              end={{x: 1, y: 0}}>
              <Text style={styles.tierBadgeText}>{tierBadge.label}</Text>
            </LinearGradient>
          </View>
          {item.at_risk && (
            <View style={styles.riskBadge}>
              <Icon name="warning" size={16} color={colors.danger} />
            </View>
          )}
        </View>

        <View style={styles.customerStats}>
          <View style={styles.stat}>
            <Icon name="calendar" size={16} color={colors.primary} />
            <Text style={styles.statValue}>{item.visit_count}</Text>
            <Text style={styles.statLabel}>visites</Text>
          </View>
          <View style={styles.stat}>
            <Icon name="cash" size={16} color={colors.success} />
            <Text style={styles.statValue}>{item.lifetime_value}€</Text>
            <Text style={styles.statLabel}>LTV</Text>
          </View>
          <View style={styles.stat}>
            <Icon name="time" size={16} color={colors.gray} />
            <Text style={styles.statValue}>{item.days_since_last_visit}j</Text>
            <Text style={styles.statLabel}>dernière</Text>
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  return (
    <View style={styles.container}>
      {/* Header */}
      <LinearGradient
        colors={colors.gradient.primary}
        style={styles.header}
        start={{x: 0, y: 0}}
        end={{x: 1, y: 1}}>
        <Text style={styles.headerTitle}>Clients</Text>
        <Text style={styles.headerSubtitle}>Gérez vos clients</Text>
      </LinearGradient>

      {/* Search Bar */}
      <View style={styles.searchContainer}>
        <Icon name="search" size={20} color={colors.gray} style={styles.searchIcon} />
        <TextInput
          style={styles.searchInput}
          placeholder="Rechercher un client..."
          placeholderTextColor={colors.grayLight}
          value={searchQuery}
          onChangeText={setSearchQuery}
        />
      </View>

      {/* Filters */}
      <View style={styles.filtersContainer}>
        <TouchableOpacity
          style={[styles.filterChip, filter === 'all' && styles.filterChipActive]}
          onPress={() => setFilter('all')}>
          <Text style={[styles.filterText, filter === 'all' && styles.filterTextActive]}>
            Tous
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterChip, filter === 'vip' && styles.filterChipActive]}
          onPress={() => setFilter('vip')}>
          <Icon name="star" size={14} color={filter === 'vip' ? colors.white : colors.warning} />
          <Text style={[styles.filterText, filter === 'vip' && styles.filterTextActive]}>
            VIP
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterChip, filter === 'at_risk' && styles.filterChipActive]}
          onPress={() => setFilter('at_risk')}>
          <Icon name="warning" size={14} color={filter === 'at_risk' ? colors.white : colors.danger} />
          <Text style={[styles.filterText, filter === 'at_risk' && styles.filterTextActive]}>
            À risque
          </Text>
        </TouchableOpacity>
      </View>

      {/* Customer List */}
      <FlatList
        data={customers}
        renderItem={renderCustomerCard}
        keyExtractor={item => item.id}
        contentContainerStyle={styles.listContent}
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
      />

      {/* Add Button */}
      <TouchableOpacity style={styles.fab}>
        <LinearGradient
          colors={colors.gradient.primary}
          style={styles.fabGradient}
          start={{x: 0, y: 0}}
          end={{x: 1, y: 1}}>
          <Icon name="add" size={28} color={colors.white} />
        </LinearGradient>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  header: {
    paddingTop: 60,
    paddingBottom: 30,
    paddingHorizontal: spacing.xl,
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
  },
  headerTitle: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.white,
  },
  headerSubtitle: {
    fontSize: typography.fontSize.sm,
    color: colors.white,
    opacity: 0.9,
    marginTop: 4,
  },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.white,
    marginHorizontal: spacing.base,
    marginTop: -20,
    marginBottom: spacing.base,
    paddingHorizontal: spacing.base,
    borderRadius: borderRadius.lg,
    ...shadows.md,
  },
  searchIcon: {
    marginRight: spacing.sm,
  },
  searchInput: {
    flex: 1,
    height: 50,
    fontSize: typography.fontSize.base,
    color: colors.text,
  },
  filtersContainer: {
    flexDirection: 'row',
    paddingHorizontal: spacing.base,
    marginBottom: spacing.base,
    gap: spacing.sm,
  },
  filterChip: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: spacing.base,
    paddingVertical: spacing.sm,
    borderRadius: borderRadius.full,
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 4,
  },
  filterChipActive: {
    backgroundColor: colors.primary,
    borderColor: colors.primary,
  },
  filterText: {
    fontSize: typography.fontSize.sm,
    color: colors.text,
    fontWeight: '600',
  },
  filterTextActive: {
    color: colors.white,
  },
  listContent: {
    padding: spacing.base,
  },
  customerCard: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    marginBottom: spacing.base,
    ...shadows.sm,
  },
  atRiskCard: {
    borderLeftWidth: 4,
    borderLeftColor: colors.danger,
  },
  customerHeader: {
    flexDirection: 'row',
    marginBottom: spacing.base,
  },
  avatar: {
    width: 50,
    height: 50,
    borderRadius: borderRadius.md,
    backgroundColor: colors.primary,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: spacing.sm,
  },
  avatarText: {
    fontSize: typography.fontSize.base,
    fontWeight: 'bold',
    color: colors.white,
  },
  customerInfo: {
    flex: 1,
  },
  customerName: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 2,
  },
  customerEmail: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginBottom: spacing.xs,
  },
  tierBadge: {
    alignSelf: 'flex-start',
    paddingHorizontal: spacing.sm,
    paddingVertical: 4,
    borderRadius: borderRadius.sm,
  },
  tierBadgeText: {
    fontSize: typography.fontSize.xs,
    fontWeight: '600',
    color: colors.white,
  },
  riskBadge: {
    width: 32,
    height: 32,
    borderRadius: borderRadius.md,
    backgroundColor: `${colors.danger}15`,
    justifyContent: 'center',
    alignItems: 'center',
  },
  customerStats: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    borderTopWidth: 1,
    borderTopColor: colors.borderLight,
    paddingTop: spacing.sm,
  },
  stat: {
    alignItems: 'center',
    gap: 4,
  },
  statValue: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.text,
  },
  statLabel: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
  },
  fab: {
    position: 'absolute',
    right: spacing.base,
    bottom: spacing.base,
    borderRadius: borderRadius.full,
    ...shadows.lg,
  },
  fabGradient: {
    width: 56,
    height: 56,
    borderRadius: borderRadius.full,
    justifyContent: 'center',
    alignItems: 'center',
  },
});

export default CustomersListScreen;
